<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Lengkap</th>
            <th>Kode User</th>
            <th>Password</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($user as $index => $u)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $u->karyawan->nama_lengkap ?? null }}</td>
                <td>{{ $u->kode_user ?? '-' }}</td>
                <td>123456</td>
            </tr>
        @endforeach
    </tbody>
</table>
