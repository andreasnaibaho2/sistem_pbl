@extends('layouts.app')
@section('title', 'Input Penilaian Dosen')
@section('content')
@php
$semuaAspek = [
    'lit_informasi' => ['label'=>'Literasi Informasi','kategori'=>'Literacy Skills','bobot'=>5,'opsi'=>[10=>'Menggunakan informasi tanpa menggunakan etika yang benar.',28=>'Tidak mengerti dengan apa yang dicari.',46=>'Menggunakan informasi dengan menggunakan etika yang benar.',64=>'Tidak terlalu mengerti dengan apa yang dicari.',82=>'Menggunakan informasi dengan etika yang benar secara konsisten.',100=>'Mengerti dengan apa yang dicari secara mendalam.']],
    'lit_media' => ['label'=>'Literasi Media','kategori'=>'Literacy Skills','bobot'=>5,'opsi'=>[10=>'Tidak tepat dalam menggunakan sumber.',28=>'Tidak mempertimbangkan kualitas informasi.',46=>'Melakukan identifikasi sebagian sumber dengan tepat walaupun masih ada yang belum tepat.',64=>'Memahami bahwa kualitas informasi perlu dipertimbangkan walaupun belum menyeluruh.',82=>'Melakukan identifikasi sumber dengan tepat dan sesuai.',100=>'Menilai kualitas informasi secara menyeluruh dengan mempertimbangkan keakuratan dan kredibilitas.']],
    'lit_teknologi' => ['label'=>'Literasi Teknologi','kategori'=>'Literacy Skills','bobot'=>5,'opsi'=>[10=>'Tidak mampu menggunakan, mengelola, memahami, dan menggunakan teknologi yang sesuai.',55=>'Berusaha menggunakan dan memahami teknologi meskipun masih ada kendala.',100=>'Mampu menggunakan, mengelola, memahami, dan menggunakan teknologi yang sesuai.']],
    'pr_konten' => ['label'=>'Konten Presentasi','kategori'=>'Presentasi','bobot'=>3,'opsi'=>[10=>'Informasi penting tidak disampaikan; penyampaian tidak rinci sehingga audiens bingung.',55=>'Informasi penting disampaikan secara lengkap dan berupaya menjelaskan rinci walaupun masih ada pertanyaan.',100=>'Menyajikan informasi dengan lengkap dan jelas. Penyampaian rinci sehingga audiens mengerti.']],
    'pr_visual' => ['label'=>'Tampilan Visual Presentasi','kategori'=>'Presentasi','bobot'=>3,'opsi'=>[10=>'Tampilannya penuh dengan teks, tidak ada gambar atau grafik.',28=>'Judul tidak sesuai dengan apa yang ditampilkan.',46=>'Tampilan diselingi dengan beberapa gambar/grafik/tabel.',64=>'Terdapat beberapa judul yang tidak sesuai.',82=>'Dalam tampilan gambar/tabel/grafik dan teks ditampilkan seimbang sehingga audiens tertarik.',100=>'Judul sesuai dengan yang ditampilkan.']],
    'pr_kosakata' => ['label'=>'Pemilihan Kosakata','kategori'=>'Presentasi','bobot'=>3,'opsi'=>[10=>'Sering menggunakan kata berulang-ulang.',28=>'Menggunakan kata yang tidak formal dalam penyampaian.',46=>'Sedikit sekali menggunakan kata berulang.',64=>'Sebagian dari penyampaian menggunakan kata-kata yang tidak formal.',82=>'Lancar menyampaikan presentasi, tidak gugup, tidak menggunakan kata berulang.',100=>'Dalam penyampaian menggunakan kata-kata formal dan mudah dipahami.']],
    'pr_tanya_jawab' => ['label'=>'Tanya Jawab','kategori'=>'Presentasi','bobot'=>3,'opsi'=>[10=>'Tidak bisa menjawab satupun pertanyaan dari audiens.',55=>'Mampu menjawab pertanyaan audiens walaupun tidak semuanya dan masih ada kesalahan.',100=>'Mampu menjawab semua pertanyaan audiens dengan jelas.']],
    'pr_mata_gerak' => ['label'=>'Mata & Gerak Tubuh','kategori'=>'Presentasi','bobot'=>3,'opsi'=>[10=>'Tidak melihat audiens.',18=>'Hanya membaca slide, tidak ada pengembangan.',26=>'Tidak ada gerakan tubuh (monoton).',35=>'Gelisah, tidak tenang.',43=>'Sesekali melihat kepada audiens.',51=>'Mencoba mengembangkan isi dari beberapa slide.',59=>'Menggunakan gerakan tubuh tetapi tidak natural.',67=>'Tidak gelisah dan cukup tenang.',75=>'Menjaga kontak mata dengan audiens.',84=>'Tidak terpaku pada teks di slide dan mengembangkan isi slide presentasi.',92=>'Menggunakan gerakan tubuh yang tidak dibuat-buat.',100=>'Tenang dan percaya diri.']],
    'la_penulisan' => ['label'=>'Penulisan Laporan','kategori'=>'Laporan Akhir','bobot'=>5,'opsi'=>[10=>'Banyak ditemukan kesalahan dalam pengetikan.',20=>'Banyak kalimat yang sulit dipahami.',30=>'Dokumen tidak selesai.',40=>'Penomoran untuk tabel, gambar dan grafik tidak sesuai.',50=>'Tidak ditemukan kesalahan pengetikan.',60=>'Kalimat-kalimat mudah dipahami.',70=>'Sebagian masih ditemukan kesalahan dalam penomoran tabel, grafik, dan gambar.',80=>'Tidak ditemukan kesalahan pengetikan (konsisten).',90=>'Kalimat-kalimat mudah dipahami dan runtut.',100=>'Penomoran tabel, grafik dan gambar sudah sesuai.']],
    'la_pilihan_kata' => ['label'=>'Pilihan Kata','kategori'=>'Laporan Akhir','bobot'=>5,'opsi'=>[10=>'50% dari penulisan laporan menggunakan kata-kata yang tidak formal.',28=>'Banyak ditemukan penulisan kata dalam bentuk singkatan.',46=>'20% dari penulisan laporan menggunakan kata-kata yang tidak formal.',64=>'Tidak ditemukan penulisan kata dalam bentuk singkatan.',82=>'Penulisan laporan semuanya menggunakan kata-kata formal.',100=>'Tidak ditemukan penulisan kata-kata dalam bentuk singkatan.']],
    'la_konten' => ['label'=>'Konten Laporan Akhir','kategori'=>'Laporan Akhir','bobot'=>5,'opsi'=>[10=>'Informasi yang disampaikan tidak jelas, tidak akurat, tidak relevan.',21=>'Berdasarkan hasil investigasi banyak ditemukan hasil copy paste tanpa elaborasi.',32=>'Isi dari laporan tidak sesuai dengan proyek yang dibuat.',44=>'Informasi yang disampaikan akurat, jelas dan relevan.',55=>'Dari hasil pencarian masih ada ditemukan hasil copy paste tanpa elaborasi.',66=>'30% dari isi laporan tidak sesuai dengan proyek yang dibuat.',78=>'Informasi yang disampaikan akurat, jelas dan relevan (konsisten).',89=>'Dari hasil pencarian sedikit ditemukan copy paste.',100=>'Isi laporan semuanya sesuai dengan proyek yang dibuat.']],
];

$ikonMap = ['Literacy Skills'=>'menu_book','Presentasi'=>'co_present','Laporan Akhir'=>'description'];
$perKategori = [];
foreach ($semuaAspek as $fieldName => $aspek) {
    $perKategori[$aspek['kategori']][$fieldName] = $aspek;
}
@endphp

<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-on-surface tracking-tight">Input Penilaian</h1>
        <p class="text-sm text-on-surface-variant mt-0.5">Dosen Pengampu — bobot 45%</p>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('penilaian.dosen.store') }}" method="POST" id="formPenilaian">
        @csrf

        {{-- PILIH SUPERVISI & MAHASISWA --}}
        <div class="bg-white rounded-[1.5rem] border border-outline-variant/20 shadow-sm p-7 mb-6">
            <h2 class="text-sm font-black text-on-surface uppercase tracking-widest mb-5">Pilih Mahasiswa</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-2">Mata Kuliah Supervisi</label>
                    <select name="supervisi_matkul_id" id="supervisiSelect" required
                        class="w-full px-4 py-3 rounded-xl border border-outline-variant/30 text-sm font-medium text-on-surface bg-surface-container-low focus:outline-none focus:border-primary"
                        onchange="loadMahasiswaDosen(this)">
                        <option value="">-- Pilih Mata Kuliah --</option>
                        @foreach($supervisiList as $supervisi)
                        <option value="{{ $supervisi->id }}"
                            data-mahasiswa='@json(["id"=>$supervisi->mahasiswa->id,"nama"=>$supervisi->mahasiswa->nama,"nim"=>$supervisi->mahasiswa->nim])'>
                            {{ $supervisi->mataKuliah->nama_matkul }} — {{ $supervisi->mahasiswa->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-2">Mahasiswa</label>
                    <input type="hidden" name="mahasiswa_id" id="inputMahasiswaId">
                    <input type="text" id="inputMahasiswaNama" readonly
                        placeholder="Otomatis terisi..."
                        class="w-full px-4 py-3 rounded-xl border border-outline-variant/30 text-sm text-on-surface bg-surface-container-low cursor-not-allowed">
                </div>
            </div>
        </div>

        {{-- ASPEK PER KATEGORI --}}
        @foreach($perKategori as $namaKategori => $aspekList)
        @php $ikon = $ikonMap[$namaKategori] ?? 'star'; $slugKat = strtolower(str_replace(' ','-',$namaKategori)); @endphp
        <div class="bg-white rounded-[1.5rem] border border-outline-variant/20 shadow-sm mb-6 overflow-hidden">
            <div class="flex items-center justify-between px-7 py-5 border-b border-outline-variant/10" style="background:linear-gradient(135deg,#f0fdfa,#ffffff);">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#0d4d4d;">
                        <span class="material-symbols-outlined text-lg" style="color:#5eead4;">{{ $ikon }}</span>
                    </div>
                    <div>
                        <p class="font-black text-on-surface text-sm">{{ $namaKategori }}</p>
                        <p class="text-[10px] text-on-surface-variant">{{ count($aspekList) }} aspek</p>
                    </div>
                </div>
                <span class="text-2xl font-black" style="color:#0d9488;" id="total-{{ $slugKat }}">0%</span>
            </div>

            @foreach($aspekList as $fieldName => $aspek)
            @php $slugKat2 = strtolower(str_replace(' ','-',$aspek['kategori'])); @endphp
            <div class="px-7 py-6 border-b border-outline-variant/10 last:border-b-0">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="font-black text-on-surface text-sm">{{ $aspek['label'] }}</p>
                        <p class="text-[10px] text-on-surface-variant">Bobot {{ $aspek['bobot'] }}%</p>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-on-surface-variant">Poin terpilih</span>
                        <p class="font-black text-lg" style="color:#0d9488;" id="preview-{{ $fieldName }}">—</p>
                    </div>
                </div>
                <input type="hidden" name="{{ $fieldName }}" id="val-{{ $fieldName }}" value="">
                <div class="space-y-2" id="opsi-{{ $fieldName }}">
                    @foreach($aspek['opsi'] as $poin => $deskripsi)
                    <div class="rubrik-opsi-item flex items-start gap-3 p-3.5 rounded-xl cursor-pointer transition-all"
                         style="border: 2px solid transparent;"
                         data-field="{{ $fieldName }}"
                         data-poin="{{ $poin }}"
                         data-slugkat="{{ $slugKat2 }}"
                         onclick="pilihOpsi('{{ $fieldName }}', {{ $poin }}, '{{ $slugKat2 }}', this)">
                        <div class="w-5 h-5 rounded-full border-2 flex-shrink-0 mt-0.5 flex items-center justify-center transition-all"
                             style="border-color: #d1d5db;"
                             id="dot-{{ $fieldName }}-{{ $poin }}"></div>
                        <span class="text-sm text-on-surface-variant leading-relaxed select-none">{{ $deskripsi }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endforeach

        {{-- CATATAN --}}
        <div class="bg-white rounded-[1.5rem] border border-outline-variant/20 shadow-sm p-7 mb-6">
            <label class="block text-xs font-black text-on-surface uppercase tracking-widest mb-3">Catatan Dosen</label>
            <textarea name="catatan_dosen" rows="3"
                placeholder="Catatan tambahan (opsional)..."
                class="w-full px-4 py-3 rounded-xl border border-outline-variant/30 text-sm text-on-surface bg-surface-container-low focus:outline-none focus:border-primary resize-none"></textarea>
        </div>

        {{-- SUMMARY & SUBMIT --}}
        <div class="bg-white rounded-[1.5rem] border border-outline-variant/20 shadow-sm p-7 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-1">Estimasi Nilai Dosen (45%)</p>
                    <p class="text-4xl font-black" style="color:#0d9488;" id="grandTotal">0.00</p>
                </div>
                <button type="submit"
                    class="flex items-center gap-2 px-8 py-4 rounded-2xl text-sm font-black text-white shadow-xl transition-all hover:scale-105"
                    style="background:linear-gradient(135deg,#0d4d4d,#0d9488);">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Simpan Penilaian
                </button>
            </div>
            <p class="text-[10px] text-on-surface-variant mt-3" id="infoAspekBelum">Semua aspek harus dipilih sebelum menyimpan.</p>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const bobotMap = {
    @foreach($semuaAspek as $fieldName => $aspek)
    '{{ $fieldName }}': {{ $aspek['bobot'] }},
    @endforeach
};
const kategoriMap = {
    @foreach($semuaAspek as $fieldName => $aspek)
    '{{ $fieldName }}': '{{ strtolower(str_replace(' ', '-', $aspek['kategori'])) }}',
    @endforeach
};
const semuaField = {!! json_encode(array_keys($semuaAspek)) !!};
const nilaiTerpilih = {};

function loadMahasiswaDosen(select) {
    var opt = select.options[select.selectedIndex];
    var data = opt.dataset.mahasiswa ? JSON.parse(opt.dataset.mahasiswa) : null;
    if (data) {
        document.getElementById('inputMahasiswaId').value = data.id;
        document.getElementById('inputMahasiswaNama').value = data.nama + ' (' + data.nim + ')';
    } else {
        document.getElementById('inputMahasiswaId').value = '';
        document.getElementById('inputMahasiswaNama').value = '';
    }
}

function pilihOpsi(fieldName, poin, slugKat, elKlik) {
    document.querySelectorAll('#opsi-' + fieldName + ' .rubrik-opsi-item').forEach(function(el) {
        el.style.borderColor = 'transparent';
        el.style.backgroundColor = '';
        var dot = document.getElementById('dot-' + fieldName + '-' + el.dataset.poin);
        if (dot) { dot.style.borderColor = '#d1d5db'; dot.innerHTML = ''; }
    });
    elKlik.style.borderColor = '#0d9488';
    elKlik.style.backgroundColor = 'rgba(13,148,136,0.05)';
    var dotPilih = document.getElementById('dot-' + fieldName + '-' + poin);
    if (dotPilih) {
        dotPilih.style.borderColor = '#0d9488';
        dotPilih.innerHTML = '<div style="width:10px;height:10px;background:#0d9488;border-radius:50%;"></div>';
    }
    document.getElementById('val-' + fieldName).value = poin;
    document.getElementById('preview-' + fieldName).textContent = poin;
    nilaiTerpilih[fieldName] = poin;
    updateTotalKategori(slugKat);
    updateGrandTotal();
}

function updateTotalKategori(slugKat) {
    var total = 0, max = 0;
    Object.keys(kategoriMap).forEach(function(field) {
        if (kategoriMap[field] !== slugKat) return;
        max += bobotMap[field];
        if (nilaiTerpilih[field] !== undefined) total += nilaiTerpilih[field] * (bobotMap[field] / 100);
    });
    var el = document.getElementById('total-' + slugKat);
    if (el) el.textContent = max > 0 ? Math.round((total / max) * 100) + '%' : '0%';
}

function updateGrandTotal() {
    var total = 0;
    semuaField.forEach(function(field) {
        if (nilaiTerpilih[field] !== undefined) total += nilaiTerpilih[field] * (bobotMap[field] / 100);
    });
    document.getElementById('grandTotal').textContent = total.toFixed(2);
    var belum = semuaField.filter(function(f) { return nilaiTerpilih[f] === undefined; });
    var info = document.getElementById('infoAspekBelum');
    if (belum.length === 0) { info.textContent = '✓ Semua aspek sudah dipilih.'; info.style.color = '#0d9488'; }
    else { info.textContent = belum.length + ' aspek belum dipilih.'; info.style.color = '#9ca3af'; }
}

document.getElementById('formPenilaian').addEventListener('submit', function(e) {
    var belum = semuaField.filter(function(f) { return nilaiTerpilih[f] === undefined; });
    if (belum.length > 0) {
        e.preventDefault();
        alert(belum.length + ' aspek belum dipilih. Harap pilih semua aspek terlebih dahulu.');
    }
    var mhsId = document.getElementById('inputMahasiswaId').value;
    if (!mhsId) {
        e.preventDefault();
        alert('Pilih mata kuliah supervisi terlebih dahulu.');
    }
});
</script>
@endpush