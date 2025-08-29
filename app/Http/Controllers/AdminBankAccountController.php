<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;

class AdminBankAccountController extends Controller
{
    /**
     * Tampilkan daftar rekening bank.
     */
    public function index()
    {
        $bankAccounts = BankAccount::latest()->paginate(10);
        return view('admin.bank-accounts.index', compact('bankAccounts'));
    }

    /**
     * Form buat rekening bank.
     */
    public function create()
    {
        return view('admin.bank-accounts.create');
    }

    /**
     * Simpan rekening bank baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_holder' => 'required|string|max:255',
            'account_type' => 'required|in:savings,current',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        BankAccount::create($request->only([
            'bank_name',
            'account_number',
            'account_holder',
            'account_type',
            'description',
            'is_active'
        ]));

        return redirect()->route('admin.bank-accounts.index')
            ->with('success', 'Rekening bank berhasil ditambahkan!');
    }

    /**
     * Form edit rekening bank.
     */
    public function edit(BankAccount $bankAccount)
    {
        return view('admin.bank-accounts.edit', compact('bankAccount'));
    }

    /**
     * Update rekening bank.
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_holder' => 'required|string|max:255',
            'account_type' => 'required|in:savings,current',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $bankAccount->update($request->only([
            'bank_name',
            'account_number',
            'account_holder',
            'account_type',
            'description',
            'is_active'
        ]));

        return redirect()->route('admin.bank-accounts.index')
            ->with('success', 'Rekening bank berhasil diperbarui!');
    }

    /**
     * Hapus rekening bank (jika tidak digunakan oleh pembayaran).
     */
    public function destroy(BankAccount $bankAccount)
    {
        if ($bankAccount->payments()->count() > 0) {
            return redirect()->route('admin.bank-accounts.index')
                ->with('error', 'Rekening bank tidak dapat dihapus karena masih digunakan dalam pembayaran!');
        }

        $bankAccount->delete();

        return redirect()->route('admin.bank-accounts.index')
            ->with('success', 'Rekening bank berhasil dihapus!');
    }

    /**
     * Toggle status aktif/nonaktif rekening bank.
     */
    public function toggleStatus(BankAccount $bankAccount)
    {
        $bankAccount->update(['is_active' => !$bankAccount->is_active]);

        $status = $bankAccount->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.bank-accounts.index')
            ->with('success', "Rekening bank berhasil {$status}!");
    }
}
