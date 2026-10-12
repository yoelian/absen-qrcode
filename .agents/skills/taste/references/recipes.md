# Taste UI Recipes & Style Snippets

Reusable design tokens and styling snippets for crafting high-taste interfaces.

---

## 1. Bento Card with Ambient Glow
```html
<div class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02),0_10px_20px_-5px_rgba(0,0,0,0.03)] hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
    <div class="flex items-center justify-between mb-4">
        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Kehadiran</span>
        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-semibold">
            <i class="bi bi-check2-circle"></i>
        </div>
    </div>
    <div class="text-3xl font-bold tracking-tight text-slate-900 tabular-nums">98.4%</div>
    <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
        <span class="text-emerald-600 font-semibold flex items-center">↑ 2.1%</span> vs minggu lalu
    </p>
</div>
```

---

## 2. Tactile Button with Active Press State
```html
<button class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl font-medium text-sm text-white bg-slate-900 hover:bg-slate-800 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition-all duration-150 shadow-sm cursor-pointer">
    <i class="bi bi-plus-lg mr-2 text-xs"></i>
    Tambah Siswa
</button>
```

---

## 3. High-Craft Data Table
```html
<div class="overflow-hidden rounded-2xl border border-slate-200/60 bg-white shadow-sm">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                <th class="py-3.5 px-5">Nama Lengkap</th>
                <th class="py-3.5 px-4">Kelas / Jabatan</th>
                <th class="py-3.5 px-4 text-center">Status</th>
                <th class="py-3.5 px-4 text-right">Jam Masuk</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm">
            <tr class="hover:bg-slate-50/70 transition-colors group">
                <td class="py-3 px-5 font-medium text-slate-800">Achmad Yulian</td>
                <td class="py-3 px-4 text-slate-500">XI Rekayasa Perangkat Lunak</td>
                <td class="py-3 px-4 text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                        Hadir Tepat Waktu
                    </span>
                </td>
                <td class="py-3 px-4 text-right font-mono text-xs text-slate-600 tabular-nums">06:45:12</td>
            </tr>
        </tbody>
    </table>
</div>
```

---

## 4. Minimalist Empty State
```html
<div class="py-16 text-center border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/30">
    <div class="w-12 h-12 mx-auto rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-center text-slate-400 text-xl mb-3">
        <i class="bi bi-inbox"></i>
    </div>
    <h3 class="text-sm font-semibold text-slate-900">Belum Ada Catatan Absensi</h3>
    <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">Data absensi hari ini masih kosong. Silakan buka halaman scan untuk mulai mencatat.</p>
</div>
```
