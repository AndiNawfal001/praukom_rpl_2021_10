<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function dashboard () {
        $ceklogin = Auth::user();
        if($ceklogin == null){
            return redirect('login');
        }
        $submitter = Auth::user()->id_pengguna;

        $barang_masuk = DB::table('barang_masuk')->sum('jml_masuk');
        $supplier = DB::table('supplier')->count();
        $ruangan = DB::table('ruangan')->count();
        $pengajuan_bb = DB::table('pengajuan_bb')->where('status_approval', 'setuju')->count();
        $pemutihan = DB::table('pemutihan')->where('approve_penonaktifan', 'setuju')->count();
        $latest_detail_barang = DB::table('barang')
                                ->select('detail_barang.kode_barang', 'jenis_barang.nama_jenis')
                                ->join('detail_barang', 'barang.id_barang', '=', 'detail_barang.id_barang')
                                ->leftJoin('jenis_barang', 'barang.id_jenis_brg', '=', 'jenis_barang.id_jenis_brg')
                                ->orderByDesc('detail_barang.kode_barang')
                                ->paginate(5);
        $latest_logging = DB::table('log')->orderByDesc('id_log')->paginate(5);
        $bb_outstanding = DB::table('pengajuan_bb')->where('status_pembelian', NULL)->paginate(5);
        $kode_rusak = DB::table('perbaikan_pemutihan')
                ->whereNull('kode_barang')
                ->where('submitter', $submitter)
                ->paginate(10);

        return view('partials.dashboard', compact(
            'barang_masuk',
            'supplier',
            'pengajuan_bb',
            'pemutihan',
            'ruangan',
            'latest_detail_barang',
            'latest_logging',
            'bb_outstanding',
            'kode_rusak'
        ));
    }
}
