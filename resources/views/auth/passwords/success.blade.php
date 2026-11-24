@extends('layouts.auth')

@section('content')
    <div style="position:fixed;inset:0;background:rgba(0,0,0,.5);display:flex;align-items:center;justify-content:center;padding:16px;z-index:1050;">
        <div style="background:#fff;border-radius:10px;max-width:420px;width:100%;padding:32px 28px;text-align:center;box-shadow:0 10px 40px rgba(0,0,0,.25);">
            <div style="width:72px;height:72px;margin:0 auto 16px;border-radius:50%;border:4px solid #a5dc86;color:#a5dc86;font-size:40px;line-height:64px;">&#10003;</div>
            <h3 style="margin:0 0 10px;color:#25396f;">Berhasil</h3>
            <p style="margin:0 0 24px;color:#555;">Kata sandi Anda berhasil diperbarui. Silakan masuk menggunakan kata sandi baru.</p>
            <a href="{{ $loginUrl }}" class="btn btn-primary px-5">OK</a>
        </div>
    </div>
@endsection
