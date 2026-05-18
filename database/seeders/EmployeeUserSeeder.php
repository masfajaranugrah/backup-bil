<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [

            ['name' => 'ROHMAT SETIA NURSEMEDI', 'email' => 'rohmatsetianursemedi@jernih.net', 'password' => 'Rx7Kp2Qa'],
            ['name' => 'ZIDAN MUSTAQIM', 'email' => 'zidanmustaqim@jernih.net', 'password' => 'Zm4Lx8Pw'],
            ['name' => 'RIZKI ANGGA SAPUTRA', 'email' => 'rizkianggasaputra@jernih.net', 'password' => 'Ra9Tq3Zm'],
            ['name' => 'ARDIKA', 'email' => 'ardika@jernih.net', 'password' => 'Ar5Nx2Lp'],
            ['name' => 'ABDUL AZIZ ASH SHIDIQ', 'email' => 'abdulazizashshidiq@jernih.net', 'password' => 'Aa8Qw1Ze'],
            ['name' => 'DIKI SETIAWAN', 'email' => 'dikisetiawan@jernih.net', 'password' => 'Ds6Yp4Ka'],
            ['name' => 'MARSANTO WIBOWO', 'email' => 'marsantowibowo@jernih.net', 'password' => 'Mw3Rt8Qa'],
            ['name' => 'ERLANGGA DEDEI SETIAWAN', 'email' => 'erlanggadedeisetiawan@jernih.net', 'password' => 'Ed7Lp2Zm'],
            ['name' => 'CEGGY MAY SETIAWAN', 'email' => 'ceggymaysetiawan@jernih.net', 'password' => 'Cm5Qa9Tx'],
            ['name' => 'M.TRIYAS YUSUP', 'email' => 'mtriyasyusup@jernih.net', 'password' => 'Ty2Wx7Lp'],

            ['name' => 'ANGGA PERMADI', 'email' => 'anggapermadi@jernih.net', 'password' => 'Ap8Zm4Qr'],
            ['name' => 'TAUFIQ EKO PRABOWO', 'email' => 'taufiqekoprabowo@jernih.net', 'password' => 'Tp1Lx6Qa'],
            ['name' => 'SAIFUL BAHARI', 'email' => 'saifulbahari@jernih.net', 'password' => 'Sb7Qw3Zm'],
            ['name' => 'SAIFUL ANWAR', 'email' => 'saifulanwar@jernih.net', 'password' => 'Sa2Lp8Tx'],
            ['name' => 'DIMAS AKHSAN', 'email' => 'dimasakhsan@jernih.net', 'password' => 'Da9Qr4Wx'],
            ['name' => 'EDY SUSANTO', 'email' => 'edysusanto@jernih.net', 'password' => 'Es5Zm1Lp'],
            ['name' => 'EDY SURYANTO', 'email' => 'edysuryanto@jernih.net', 'password' => 'Ey8Qa2Tx'],
            ['name' => 'WISNU FARIZKY WIHANTARA', 'email' => 'wisnufarizkywihantara@jernih.net', 'password' => 'Wf4Lp7Qr'],
            ['name' => 'DIAN HERI YANUAR', 'email' => 'dianheriyanuar@jernih.net', 'password' => 'Dh6Tx3Qa'],
            ['name' => 'ANGGA SATRIA', 'email' => 'anggasatria@jernih.net', 'password' => 'As1Zm8Lp'],

            ['name' => 'DIAN RAHMAT', 'email' => 'dianrahmat@jernih.net', 'password' => 'Dr7Qa5Wx'],
            ['name' => 'TUFIK NUR CAHYO', 'email' => 'tufiknurcahyo@jernih.net', 'password' => 'Tc3Lp9Zm'],
            ['name' => 'AIRIN SITI MULYANI', 'email' => 'airinsitimulyani@jernih.net', 'password' => 'Am8Qr2Tx'],
            ['name' => 'MUH.FIRDAUS', 'email' => 'muhfirdaus@jernih.net', 'password' => 'Mf5Qa7Lp'],
            ['name' => 'ALFIAN PUTRA', 'email' => 'alfianputra@jernih.net', 'password' => 'Ap2Zm6Qr'],
            ['name' => 'BAGAS AHMAT FAUZI', 'email' => 'bagasahmatfauzi@jernih.net', 'password' => 'Bf9Tx1Lp'],
            ['name' => 'TRIYONO', 'email' => 'triyono@jernih.net', 'password' => 'Tr4Qa8Zm'],
            ['name' => 'WAHYU NUGROHO', 'email' => 'wahyunugroho@jernih.net', 'password' => 'Wn7Lp3Qr'],
            ['name' => 'DITO OKTRI SAPUTRA', 'email' => 'ditooktrisaputra@jernih.net', 'password' => 'Do5Zm2Tx'],
            ['name' => 'JULIANTO WIBOWO SETYAWAN', 'email' => 'juliantowibowosetyawan@jernih.net', 'password' => 'Js8Qa4Lp'],

            ['name' => 'DANU SETYA AGUNG', 'email' => 'danusetyaagung@jernih.net', 'password' => 'Da1Qr7Zm'],
            ['name' => 'FIKRI ROMDHONI', 'email' => 'fikriromdhoni@jernih.net', 'password' => 'Fr6Lp9Tx'],
            ['name' => 'MUH.RADITYA', 'email' => 'muhraditya@jernih.net', 'password' => 'Mr3Qa5Zm'],
            ['name' => 'DIMAS RAIHAN', 'email' => 'dimasraihan@jernih.net', 'password' => 'Dr8Tx2Lp'],
            ['name' => 'FEBRIAN KOES ARADIT', 'email' => 'febriankoesaradit@jernih.net', 'password' => 'Fa4Zm7Qr'],
            ['name' => 'SUKARDI', 'email' => 'sukardi@jernih.net', 'password' => 'Sk1Lp6Qa'],
            ['name' => 'FAIS FITRIYANTO', 'email' => 'faisfitriyanto@jernih.net', 'password' => 'Ff9Qr3Tx'],
            ['name' => 'FAJAR ANUGRAH KRISNA DWINARTA', 'email' => 'fajaranugrahkrisnadwinarta@jernih.net', 'password' => 'Fd5Zm8Lp'],
            ['name' => 'ARDILLES FAJAR FITRI', 'email' => 'ardillesfajarfitri@jernih.net', 'password' => 'Af2Qa7Qr'],
            ['name' => 'AJI SUKMA WIJAYA', 'email' => 'ajisukmawijaya@jernih.net', 'password' => 'Aw6Lp1Zm'],

            ['name' => 'UNTUNG', 'email' => 'untung@jernih.net', 'password' => 'Un3Tx9Qa'],
            ['name' => 'RATNA EKA SAPUTRI', 'email' => 'ratnaekasaputri@jernih.net', 'password' => 'Rs8Zm4Lp'],
            ['name' => 'CANDRA LELANA', 'email' => 'candralelana@jernih.net', 'password' => 'Cl5Qa2Qr'],
            ['name' => 'Reffin Suryawan', 'email' => 'reffinsuryawan@jernih.net', 'password' => 'Rs7Lp8Tx'],
            ['name' => 'Dandi Krismanto', 'email' => 'dandikrismanto@jernih.net', 'password' => 'Dk1Qa6Zm'],
            ['name' => 'Sunan Wijayanto', 'email' => 'sunanwijayanto@jernih.net', 'password' => 'Sw4Qr9Lp'],
            ['name' => 'Sukiyantoro', 'email' => 'sukiyantoro@jernih.net', 'password' => 'Sy2Tx5Qa'],
            ['name' => 'YUSUF HIDAYAT RAMADHAN', 'email' => 'yusufhidayatramadhan@jernih.net', 'password' => 'Yr8Lp3Zm'],
            ['name' => 'NANDA NUR RIZKY', 'email' => 'nandanurrizky@jernih.net', 'password' => 'Nr6Qa1Tx'],
            ['name' => 'Agus Purnama', 'email' => 'aguspurnama@jernih.net', 'password' => 'Ap9Zm7Lp'],

            ['name' => 'Gelis', 'email' => 'gelis@jernih.net', 'password' => 'Ge5Qr2Qa'],
            ['name' => 'Aprilia', 'email' => 'aprilia@jernih.net', 'password' => 'Ar1Lp8Tx'],
            ['name' => 'Yohana', 'email' => 'yohana@jernih.net', 'password' => 'Yo7Qa4Zm'],
            ['name' => 'Firdaus', 'email' => 'firdaus@jernih.net', 'password' => 'Fi3Tx9Lp'],
        ];

        foreach ($users as $user) {

            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make($user['password']),
                    'role' => 'karyawan',
                ]
            );
        }
    }
}
