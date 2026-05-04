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
}
