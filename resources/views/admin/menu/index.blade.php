@include('partials.header')

<div class="content-wrapper">
    <br>
    @include('partials.loader')
    <section class="content">
        <div class="container-fluid mt-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header" style="background-color: #6a0dad;">
                            <h3 class="card-title" style="color: gold; font-weight: bold;">
                                Settings Menu
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Minimal</label>
                                    <select class="form-control select2" style="width: 100%;">
                                        <option selected="selected">--Pilih Akses--</option>
                                        @foreach ($role as $role)
                                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <form id="roleAccessForm">
                                    <div class="row">
                                        <!-- Tabel Menu -->
                                        <div class="col-md-6">
                                            <h5>Menus</h5>
                                            <div class="scrollable-table">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:50px">Pilih</th>
                                                            <th>Menu / Submenu</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="menu-table-body"></tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- Tabel Permission -->
                                        <div class="col-md-6">
                                            <h5>Permissions</h5>
                                            <div class="scrollable-table">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:50px">Pilih</th>
                                                            <th>Permission</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="permission-table-body"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    @if (auth()->user()->hasPermission('update_menu_setting'))
                                        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                                    @endif
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    /* Scroll khusus tabel */
    .scrollable-table {
        max-height: 60vh !important;
        overflow-y: auto !important;
        padding-right: 6px;
        /* biar scrollbar tidak nutup teks */
    }

    /* Styling scrollbar (opsional) */
    .scrollable-table::-webkit-scrollbar {
        width: 8px;
    }

    .scrollable-table::-webkit-scrollbar-thumb {
        background: #6a0dad;
        border-radius: 10px;
    }
</style>

@include('partials.script')
@include('admin.menu.script_menu')
