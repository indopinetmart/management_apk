<!--//LOADER LOGOUT -->
<div id="logout-loader" style="display: none;">
    <div class="loader-content">
        <img id="loader-image" src="{{ asset('assets/images/loader/spiner_gif.gif') }}" alt="Loading...">
        <p id="loader-text">Sedang Menutup...</p>
    </div>
</div>


<!--//LOADER PROSES -->
<div id="proses-loader"
    style="position:fixed;top:0;left:0;width:100%;height:100%;
            background:rgba(0,0,0,0.6);backdrop-filter:blur(3px);
            display:none;justify-content:center;align-items:center;
            z-index:9999;flex-direction:column;">
    <div style="text-align:center;color:#fff;">
        <img id="loader-image" src="{{ asset('assets/images/loader/logout.gif') }}" alt="Loading..."
            style="width:120px;height:120px;display:block;margin:0 auto;">
        <p id="loader-text" style="margin-top:12px;font-size:18px;font-weight:600;">
            Mohon Tunggu...
        </p>
    </div>
</div>

<!-- Loader untuk Face-API -->
<div id="loadingModels"
    style="display:none;
           position: fixed;
           top: 0;
           left: 0;
           width: 100%;
           height: 100%;
           background: rgba(255, 255, 255, 0.95);
           z-index: 9999;
           flex-direction: column;
           text-align: center;"
    class="loader-center">

    <!-- Teks Loading -->
    <p
        style="font-size:20px;
              font-weight:bold;
              color:#333;
              margin-bottom:15px;">
        🔄 Please Wait...
    </p>

    <!-- Progress Bar Container -->
    <div
        style="width:300px;
                height:20px;
                background:#eee;
                border-radius:5px;
                overflow:hidden;">
        <div id="modelProgress"
            style="width:0%;
                   height:100%;
                   background:#4caf50;
                   transition: width 0.3s ease;">
        </div>
    </div>

    <!-- Persentase Progress -->
    <p id="progressText"
        style="margin-top:10px;
              font-weight:bold;
              color:#555;
              font-size:14px;">
        0%
    </p>
</div>
