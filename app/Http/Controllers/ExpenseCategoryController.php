<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ExpenseCategory::with('creator')->withSum('expenses', 'amount')->paginate(10);
        return view('expense_categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expense_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'budget_limit' => 'nullable|numeric|min:0',
        ]);

        ExpenseCategory::create([
            'name' => $request->name,
            'budget_limit' => $request->budget_limit,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('expense-categories.index')->with('success', 'Saluran pengeluaran berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->load(['expenses.creator', 'creator']);
        $totalPengeluaran = $expenseCategory->expenses()->sum('amount');
        return view('expense_categories.show', compact('expenseCategory', 'totalPengeluaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('expense_categories.edit', compact('expenseCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'budget_limit' => 'nullable|numeric|min:0',
        ]);

        $expenseCategory->update([
            'name' => $request->name,
            'budget_limit' => $request->budget_limit,
        ]);

        return redirect()->route('expense-categories.index')->with('success', 'Saluran pengeluaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->delete();
        return redirect()->route('expense-categories.index')->with('success', 'Saluran pengeluaran berhasil dihapus.');
    }
    /**
     * Parse and import expenses from chat text.
     */
    public function parseText(Request $request)
    {
        $request->validate([
            'chat_text' => 'required|string',
        ]);

        $text = $request->input('chat_text');
        
        // 1. Standardize newlines and split into blocks
        $text = str_replace("\r\n", "\n", $text);
        $blocks = array_values(array_filter(array_map('trim', explode("\n\n", $text))));

        if (count($blocks) === 0) {
            return redirect()->back()->with('error', 'Teks tidak boleh kosong.');
        }

        // 2. Extract category name from the first block
        $categoryNameRaw = $blocks[0];
        $categoryName = trim(explode("\n", $categoryNameRaw)[0]);
        // Clean up trailing arrows if user included them in the chat text
        $categoryName = trim(preg_replace('/->.*$/u', '', $categoryName));

        // Find or create the category
        $category = ExpenseCategory::firstOrCreate(
            ['name' => $categoryName],
            ['created_by' => Auth::id(), 'budget_limit' => null]
        );

        // 3. Process remaining blocks as expenses
        $expensesCount = 0;
        for ($i = 1; $i < count($blocks); $i++) {
            $blockLines = array_values(array_filter(array_map('trim', explode("\n", $blocks[$i]))));
            if (empty($blockLines)) continue;

            // Extract Title from line 0
            $titleLine = $blockLines[0];
            
            // Clean title
            $title = $titleLine;
            $title = str_replace(['1️⃣', '2️⃣', '3️⃣', '4️⃣', '5️⃣', '6️⃣', '7️⃣', '8️⃣', '9️⃣', '0️⃣', '✅'], '', $title);
            $title = preg_replace('/^\d+[\.\)]?\s*/', '', $title); // Remove leading "1." or "1)"
            $title = preg_replace('/->.*$/u', '', $title); // Handle "-> Jenis pengeluaran"
            $title = trim($title);

            $amount = 0;
            $metadata = [];
            $jumlah = 0;
            $total = 0;

            for ($j = 1; $j < count($blockLines); $j++) {
                $line = $blockLines[$j];
                // Remove trailing notes like " -> total pengeluaran"
                $line = preg_replace('/->.*$/u', '', $line);
                $line = str_replace('✅', '', $line);
                $line = trim($line);

                if (stripos($line, 'Total') !== false && strpos($line, ':') !== false) {
                    $val = explode(':', $line)[1];
                    $val = preg_replace('/[^0-9]/', '', $val);
                    if (is_numeric($val)) $total = (float)$val;
                } elseif (stripos($line, 'Jumlah') !== false && strpos($line, ':') !== false) {
                    $val = explode(':', $line)[1];
                    $val = preg_replace('/[^0-9]/', '', $val);
                    if (is_numeric($val)) $jumlah = (float)$val;
                } elseif (strpos($line, ':') !== false) {
                    $metadata[] = trim($line);
                } else {
                    $metadata[] = trim($line);
                }
            }

            $amount = $total > 0 ? $total : $jumlah;
            
            if ($amount > 0) {
                $description = $title;
                if (!empty($metadata)) {
                    $description .= ' (' . implode(', ', $metadata) . ')';
                }

                \App\Models\Expense::create([
                    'expense_category_id' => $category->id,
                    'description' => $description,
                    'amount' => $amount,
                    'date' => now()->toDateString(),
                    'created_by' => Auth::id(),
                ]);
                $expensesCount++;
            }
        }

        return redirect()->route('expense-categories.show', $category)->with('success', "Berhasil memparse teks dan menambahkan $expensesCount data pengeluaran ke saluran $category->name.");
    }
}
