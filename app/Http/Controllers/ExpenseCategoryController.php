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
    public function index(Request $request)
    {
        $query = ExpenseCategory::with('creator')->withSum('expenses', 'amount');
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'terbesar') {
                $query->orderBy('expenses_sum_amount', 'desc');
            } elseif ($request->sort === 'terkecil') {
                $query->orderBy('expenses_sum_amount', 'asc');
            } elseif ($request->sort === 'terlama') {
                $query->orderBy('created_at', 'asc');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $categories = $query->paginate(12)->withQueryString();
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
    public function show(Request $request, ExpenseCategory $expenseCategory)
    {
        $expenseCategory->load('creator');
        
        // Total global amount (unfiltered)
        $totalPengeluaran = $expenseCategory->expenses()->sum('amount');

        // Filtered expenses query
        $query = $expenseCategory->expenses()->with('creator');

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Sort
        if ($request->filled('sort')) {
            if ($request->sort === 'amount_desc') {
                $query->orderBy('amount', 'desc');
            } elseif ($request->sort === 'amount_asc') {
                $query->orderBy('amount', 'asc');
            } elseif ($request->sort === 'date_asc') {
                $query->orderBy('date', 'asc');
            } else {
                $query->orderBy('date', 'desc')->latest();
            }
        } else {
            $query->orderBy('date', 'desc')->latest();
        }

        $expenses = $query->paginate(15)->withQueryString();

        return view('expense_categories.show', compact('expenseCategory', 'totalPengeluaran', 'expenses'));
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

        // Parse date from header name
        $headerDate = now()->toDateString();
        $months = ['januari' => 'jan', 'februari' => 'feb', 'maret' => 'mar', 'april' => 'apr', 'mei' => 'may', 'juni' => 'jun', 'juli' => 'jul', 'agustus' => 'aug', 'september' => 'sep', 'oktober' => 'oct', 'november' => 'nov', 'desember' => 'dec'];
        $headerEng = str_ireplace(array_keys($months), array_values($months), strtolower($categoryName));
        
        try {
            if (preg_match('/(\d{1,2})\s+(jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)/i', $headerEng, $matches)) {
                $headerDate = \Carbon\Carbon::parse($matches[1] . ' ' . $matches[2] . ' ' . date('Y'))->toDateString();
            } elseif (preg_match('/(jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)\s+(\d{1,2})/i', $headerEng, $matches)) {
                $headerDate = \Carbon\Carbon::parse($matches[2] . ' ' . $matches[1] . ' ' . date('Y'))->toDateString();
            } elseif (preg_match('/(jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)/i', $headerEng, $matches)) {
                $headerDate = \Carbon\Carbon::parse('1 ' . $matches[1] . ' ' . date('Y'))->toDateString();
            }
        } catch (\Exception $e) {}

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
            $date = $headerDate; // Default to header date

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
                } elseif ((stripos($line, 'Tanggal') === 0 || stripos($line, 'Tgl') === 0) && strpos($line, ':') !== false) {
                    $val = trim(explode(':', $line)[1]);
                    // Translate indonesian months to english for Carbon parsing
                    $months = ['januari' => 'jan', 'februari' => 'feb', 'maret' => 'mar', 'april' => 'apr', 'mei' => 'may', 'juni' => 'jun', 'juli' => 'jul', 'agustus' => 'aug', 'september' => 'sep', 'oktober' => 'oct', 'november' => 'nov', 'desember' => 'dec'];
                    $valEng = str_ireplace(array_keys($months), array_values($months), $val);
                    try {
                        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $valEng)) {
                            $date = \Carbon\Carbon::createFromFormat('d/m/Y', $valEng)->toDateString();
                        } else {
                            $date = \Carbon\Carbon::parse($valEng)->toDateString();
                        }
                    } catch (\Exception $e) {}
                    // Skip adding to metadata so it doesn't clutter description
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
                    'date' => $date,
                    'created_by' => Auth::id(),
                ]);
                $expensesCount++;
            }
        }

        return redirect()->route('expense-categories.show', $category)->with('success', "Berhasil memparse teks dan menambahkan $expensesCount data pengeluaran ke saluran $category->name.");
    }
}
