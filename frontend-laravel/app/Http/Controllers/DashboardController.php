<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Filter tanggal
        $start = $request->query('start')
            ? Carbon::parse($request->query('start'))->startOfDay()
            : Carbon::now()->subMonth()->startOfDay();
        $end = $request->query('end')
            ? Carbon::parse($request->query('end'))->endOfDay()
            : Carbon::now()->endOfDay();

        // Ambil semua produk
        $productsRes = Http::get('http://localhost:8000/products');
        $products = collect($productsRes->json('data') ?? []);

        // Ambil semua transaksi
        $transactionsRes = Http::get('http://localhost:8000/transactions', [
            'page' => 1,
            'limit' => 1000,
        ]);
        $transactions = collect($transactionsRes->json('data') ?? [])
            ->filter(function ($t) use ($start, $end) {
                if (!isset($t['created_at'])) return false;
                $created = Carbon::parse($t['created_at']);
                return $created->between($start, $end);
            });

        // Total produk & transaksi
        $totalProducts = $products->count();
        $totalTransactions = $transactions->count();

        // Produk stok rendah (<=5)
        $lowStock = $products->filter(fn($p) => $p['stock'] <= 5)->count();

        // Nilai inventori
        $inventoryValue = $products->sum(fn($p) => $p['price'] * $p['stock']);

        // Penjualan per bulan
        $monthlySalesRaw = $transactions
            ->groupBy(fn($t) => Carbon::parse($t['created_at'])->format('Y-m'))
            ->map(fn($monthTx) => $monthTx->sum(fn($t) => $t['quantity'] * $t['price']));

        $monthlySales = [
            'labels' => $monthlySalesRaw->keys(),
            'data' => $monthlySalesRaw->values(),
        ];

        // Penjualan per kategori
        $categorySalesRaw = $transactions
            ->groupBy(fn($t) => $t['category'] ?? 'Lainnya')
            ->map(fn($catTx) => $catTx->sum(fn($t) => $t['quantity'] * $t['price']));

        $categorySales = [
            'labels' => $categorySalesRaw->keys(),
            'data' => $categorySalesRaw->values(),
        ];

        // 10 produk terlaris berdasarkan nilai penjualan
        $topProducts = $products->map(function ($p) use ($transactions) {
            $totalSold = $transactions
                ->where('productId', $p['id'])
                ->sum(fn($t) => $t['quantity']);
            $totalValue = $transactions
                ->where('productId', $p['id'])
                ->sum(fn($t) => $t['quantity'] * $t['price']);
            return array_merge($p, [
                'total_sold' => $totalSold,
                'total_value' => $totalValue
            ]);
        })->sortByDesc('total_value')->take(10);

        return view('dashboard', [
            'products' => ['total' => $totalProducts],
            'transactions' => ['total' => $totalTransactions],
            'lowStock' => ['total' => $lowStock],
            'inventoryValue' => ['totalValue' => $inventoryValue],
            'monthlySales' => $monthlySales,
            'categorySales' => $categorySales,
            'topProducts' => $topProducts,
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d'),
        ]);
    }
}
