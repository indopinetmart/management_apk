@include('partials.header')

<div class="content-wrapper">
    <br>
    @include('partials.loader')
    <section class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    @if (auth()->user()->hasPermission('create_user'))
                        <a href="#" class="btn btn-primary btn-xs tambah-user" title="Tambah User">
                            <i class="fas fa-plus"></i> User
                        </a>
                    @endif

                </div>
            </div>
        </div>

        <div class="container-fluid mt-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header" style="background-color: #6a0dad;">
                            <h3 class="card-title" style="color: gold; font-weight: bold;">
                                Daftar Pengguna
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="user_table" class="table table-bordered table-striped">
                                <thead>
                                    <tr class="text text-center">
                                        <th>No</th>
                                        <th>Foto </th>
                                        <th>Name </th>
                                        <th>Email</th>
                                        <th>Access</th>
                                        <th>Status</th>
                                        <th>Created_by</th>
                                        <th>Updated_by</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <td class="text text-center">{{ $user['index'] }}</td>
                                            <td class="text text-center">
                                                <img src="{{ $user['photo'] }}" alt="Foto" width="35"
                                                    height="35" style="border-radius: 50%; object-fit: cover;">
                                            </td>
                                            <td>{{ $user['name'] }}</td>
                                            <td>{{ $user['email'] }}</td>
                                            <td class="text text-center">{{ $user['role_name'] }}</td>
                                            <td class="text text-center">
                                                <span
                                                    class="badge bg-{{ $user['status_badge'] }}">{{ $user['status_text'] }}</span>
                                            </td>
                                            <td class="text-center">{{ $user['created_by'] }}</td>
                                            <td class="text-center">{{ $user['updated_by'] }}</td>

                                            <td class="text text-center">
                                                @if (auth()->user()->hasPermission('edit_user'))
                                                    <a href="#" class="btn btn-warning btn-xs mr-1 edit-user"
                                                        data-id="{{ $user['id'] }}" title="Edit User">
                                                        <i class="fas fa-user-edit"></i>
                                                    </a>
                                                @endif

                                                @if (auth()->user()->hasPermission('delete_user'))
                                                    <a href="#" class="btn btn-danger btn-xs delete-user mr-1"
                                                        data-id="{{ $user['id'] }}" title="Hapus User">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                @endif

                                                @if (auth()->user()->hasPermission('aktif_nonaktif_user'))
                                                    @if ($user['status'] === 'aktif')
                                                        <a href="#"
                                                            class="btn btn-success btn-xs mr-1 nonaktifkan-user"
                                                            data-id="{{ $user['id'] }}" title="Nonaktifkan">
                                                            <i class="fa fa-arrow-up"></i>
                                                        </a>
                                                    @else
                                                        <a href="#"
                                                            class="btn btn-secondary btn-xs mr-1 aktifkan-user"
                                                            data-id="{{ $user['id'] }}" title="Aktifkan">
                                                            <i class="fa fa-arrow-down"></i>
                                                        </a>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data pengguna.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <!-- /.Modal Tambah Staff -->
    <div class="modal fade" id="tambah-user">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header " style="background-color: #6a0dad;">
                    <h6 class="modal-title" style="color: gold; font-weight: bold;">Tambah Data User</h6>
                    <button type="button" class="close" style="color: gold; font-weight: bold;" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <!--content-->
                    <div class="row">
                        <div class="col-12 col-lg-6 col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Lengkap*</label>
                                <input type="text" class="form-control mb-1" id="name" name="name"
                                    placeholder="Nama Lengkap Sesuai Nomor NIP">
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-md-6">
                            <div class="form-group">
                                <label for="email">Email*</label>
                                <input type="email" class="form-control mb-1" id="email" name="email"
                                    placeholder="Wajib Email Aktif">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-6 col-md-6">
                            <div class="form-group">
                                <label for="akses">Akses</label>
                                <select class="form form-control" name="role" id="role" style="width: 100%;">
                                    <option value="">Pilih Akses</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-md-6">
                            <div class="form-group">
                                <label for="password">Password*</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="********">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="togglePassword"
                                            style="cursor:pointer; height:38px; padding:0 10px;">
                                            <i class="fa fa-eye" style="font-size:16px;"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success simpan">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.Modal Tambah Staff -->


    <!-- Modal Delete User -->
    <div class="modal fade" id="delete-user" tabindex="-1" role="dialog" aria-labelledby="deleteUserLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header " style="background-color: #6a0dad;">
                    <h6 class="modal-title" style="color: gold; font-weight: bold;">Hapus Data User</h6>
                    <button type="button" class="close" style="color: gold; font-weight: bold;"
                        data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data pengguna berikut ini?</p>

                    <input type="hidden" id="id">

                    <div class="form-group">
                        <label>Nama Pengguna</label>
                        <input type="text" class="form-control" id="name_delete" disabled>
                    </div>

                    <div class="form-group">
                        <label>Akses / Role</label>
                        <input type="text" class="form-control" id="role_name_delete" disabled>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger hapus">Hapus</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Delete User -->


    <!-- / Modal Tambah Edit -->
    <div class="modal fade" id="edit-user">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header " style="background-color: #6a0dad;">
                    <h6 class="modal-title" style="color: gold; font-weight: bold;">Edit Data User</h6>
                    <button type="button" class="close" style="color: gold; font-weight: bold;"
                        data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="alert alert-danger d-none"></div>

                    <!--content-->
                    <div class="row">
                        <input type="hidden" class="form-control" name="id" id="id">
                        <div class="col-12 col-lg-6 col-md-6">
                            <div class="form-group">
                                <label for="name_edit">Nama Lengkap*</label>
                                <input type="text" class="form-control mb-1" id="name_edit" name="name_edit"
                                    placeholder="Nama Lengkap Sesuai Nomor NIP">
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-md-6">
                            <div class="form-group">
                                <label for="email_edit">Email*</label>
                                <input type="email" class="form-control mb-1" id="email_edit" name="email_edit"
                                    placeholder="Email Aktif">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-6 col-md-6">
                            <div class="form-group">
                                <label for="role_name_edit">Akses</label>
                                <select class="form form-control" name="role_name_edit"
                                    id="role_name_edit"style="width: 100%;">
                                    <option value="">Pilih Akses</option>
                                    @foreach ($roles as $r)
                                        <option value="{{ $r->id }}"
                                            {{ $r->id == old('role_name_edit') ? 'selected' : '' }}>
                                            {{ ucfirst($r->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6 col-md-6">
                            <div class="form-group">
                                <label for="password">Password*</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_edit"
                                        name="password_edit" placeholder="********">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="togglePasswordEdit"
                                            style="cursor:pointer; height:38px; padding:0 10px;">
                                            <i class="fa fa-eye" style="font-size:16px;"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--/. content-->
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success edit">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.Modal Tambah Edit -->


</div>
@include('partials.script')
@include('admin.users.script_user')
