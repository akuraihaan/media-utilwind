<div class="relative rounded-xl overflow-hidden bg-[#0d1117] border border-white/10 my-6 group shadow-lg">
    <div class="flex justify-between items-center px-4 py-2 bg-white/5 border-b border-white/5">
        <span class="text-[10px] font-mono text-slate-400">{{ $filename ?? 'example.html' }}</span>
        <button class="text-[10px] text-slate-400 hover:text-white transition opacity-0 group-hover:opacity-100">Copy</button>
    </div>
    <div class="p-4 overflow-x-auto custom-scrollbar">
        <pre class="font-mono text-sm leading-relaxed text-slate-300"><code>{{ $slot }}</code></pre>
    </div>
</div>