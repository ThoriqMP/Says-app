# Dokumentasi API Says-App

Dokumentasi ini menjelaskan cara menggunakan API RESTful untuk aplikasi Says-App. Semua respons dari API menggunakan format JSON.

## Informasi Umum

### Base URL
Semua URL API diawali dengan:
```
https://your-domain.com/api
```

### Otentikasi
Sebagian besar endpoint API memerlukan otentikasi. API ini menggunakan **Laravel Sanctum** untuk otentikasi berbasis token.

Untuk melakukan permintaan ke endpoint yang terproteksi, Anda harus menyertakan header `Authorization` dengan token yang didapat saat login.

```
Authorization: Bearer <your_auth_token>
```

Selain itu, sertakan juga header `Accept`:
```
Accept: application/json
```

### Respons Paginasi
Untuk endpoint yang mengembalikan daftar data (contoh: `GET /api/subjects`), respons akan dibungkus dalam struktur paginasi seperti berikut:

```json
{
    "data": [
        // ... array of objects ...
    ],
    "links": {
        "first": "https://your-domain.com/api/resource?page=1",
        "last": "https://your-domain.com/api/resource?page=10",
        "prev": null,
        "next": "https://your-domain.com/api/resource?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 10,
        "links": [
            // ...
        ],
        "path": "https://your-domain.com/api/resource",
        "per_page": 15,
        "to": 15,
        "total": 150
    }
}
```

### Respons Error
- **422 Unprocessable Entity**: Terjadi jika validasi data yang dikirim gagal. Respons akan berisi detail error untuk setiap field.
- **404 Not Found**: Terjadi jika resource yang diminta tidak ditemukan.
- **401 Unauthorized**: Terjadi jika token otentikasi tidak valid atau tidak disertakan.
- **403 Forbidden**: Terjadi jika pengguna tidak memiliki hak akses untuk melakukan aksi tersebut.

**Contoh Respons Validasi (422):**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password field must be at least 8 characters."
        ]
    }
}
```

---

## 1. Manajemen Subjek (Subjects)
Resource ini digunakan untuk mengelola data subjek (individu yang diasesmen).

### GET `/api/subjects`
Mengambil daftar subjek dengan paginasi.

- **Query Parameters:**
  - `page` (integer, opsional): Nomor halaman yang ingin diakses.
  - `search` (string, opsional): Kata kunci untuk mencari berdasarkan nama atau nomor HP subjek.

- **Respons Sukses (200 OK):**
  Struktur paginasi yang berisi array objek subjek.
  ```json
  {
      "data": [
          {
              "id": 1,
              "name": "Budi Santoso",
              "date_of_birth": "2005-10-15",
              "age": 18,
              "gender": "male",
              "phone": "081234567890",
              "created_at": "2023-10-28 10:00:00",
              "updated_at": "2023-10-28 10:00:00"
          }
      ],
      // ... links and meta ...
  }
  ```

### POST `/api/subjects`
Membuat subjek baru.

- **Request Body (JSON):**
  - `name` (string, **required**): Nama lengkap subjek.
  - `date_of_birth` (string, opsional): Tanggal lahir format `YYYY-MM-DD`.
  - `gender` (string, opsional): `male` atau `female`.
  - `phone` (string, opsional): Nomor HP subjek.

- **Respons Sukses (201 Created):**
  Objek subjek yang baru dibuat.
  ```json
  {
      "data": {
          "id": 2,
          "name": "Citra Lestari",
          "date_of_birth": "2006-05-20",
          "age": 17,
          "gender": "female",
          "phone": "089876543210",
          "created_at": "2023-10-29 11:00:00",
          "updated_at": "2023-10-29 11:00:00"
      }
  }
  ```

### GET `/api/subjects/{id}`
Mengambil detail satu subjek.

- **Respons Sukses (200 OK):**
  Objek subjek yang diminta.

### PUT `/api/subjects/{id}`
Memperbarui data subjek. Kirim hanya field yang ingin diubah.

- **Request Body (JSON):**
  - `name` (string, opsional)
  - `date_of_birth` (string, opsional)
  - `gender` (string, opsional)
  - `phone` (string, opsional)

- **Respons Sukses (200 OK):**
  Objek subjek yang telah diperbarui.

### DELETE `/api/subjects/{id}`
Menghapus data subjek.

- **Respons Sukses (204 No Content):**
  Tidak ada konten dalam respons.

---

## 2. Manajemen Siswa (Students)
Resource ini digunakan untuk mengelola data siswa.

### GET `/api/students`
Mengambil daftar siswa dengan paginasi.

- **Query Parameters:**
  - `page` (integer, opsional): Nomor halaman.
  - `search` (string, opsional): Kata kunci untuk mencari berdasarkan nama siswa, NIS, atau nama orang tua.

- **Respons Sukses (200 OK):**
  ```json
  {
      "data": [
          {
              "id": 1,
              "nama_siswa": "Ahmad Fauzi",
              "nis": "12345",
              "nama_orang_tua": "Bapak Fauzi",
              "sekolah": "SMA Negeri 1",
              "class": "12 IPA 1",
              "created_at": "2023-10-28 10:00:00",
              "updated_at": "2023-10-28 10:00:00"
          }
      ],
      // ... links and meta ...
  }
  ```

### POST `/api/students`
Membuat siswa baru.

- **Request Body (JSON):**
  - `nama_siswa` (string, **required**)
  - `nis` (string, opsional, **unique**)
  - `nama_orang_tua` (string, opsional)
  - `sekolah` (string, opsional)
  - `class` (string, opsional)

- **Respons Sukses (201 Created):**
  Objek siswa yang baru dibuat.

### GET `/api/students/{id}`
Mengambil detail satu siswa.

### PUT `/api/students/{id}`
Memperbarui data siswa.

- **Request Body (JSON):**
  - `nama_siswa` (string, opsional)
  - `nis` (string, opsional, **unique**)
  - `nama_orang_tua` (string, opsional)
  - `sekolah` (string, opsional)
  - `class` (string, opsional)

### DELETE `/api/students/{id}`
Menghapus data siswa.

- **Respons Sukses (204 No Content)**

---

## 3. Manajemen Pengguna (Users)
Resource untuk mengelola akun pengguna (admin/guru).

### GET `/api/users`
Mengambil daftar pengguna.

- **Query Parameters:**
  - `search` (string, opsional): Cari berdasarkan nama atau email.
  - `role` (string, opsional): Filter berdasarkan peran (`admin` atau `guru`).

- **Respons Sukses (200 OK):**
  ```json
  {
      "data": [
          {
              "id": 1,
              "name": "Admin Utama",
              "email": "admin@says.app",
              "role": "admin",
              "created_at": "2023-10-28 10:00:00"
          }
      ],
      // ... links and meta ...
  }
  ```

### POST `/api/users`
Membuat pengguna baru.

- **Request Body (JSON):**
  - `name` (string, **required**)
  - `email` (string, **required**, email, **unique**)
  - `password` (string, **required**, min: 8, **confirmed**)
  - `password_confirmation` (string, **required**): Harus sama dengan `password`.
  - `role` (string, **required**): `admin` atau `guru`.

- **Respons Sukses (201 Created):**
  Objek pengguna yang baru dibuat (tanpa password).

### PUT `/api/users/{id}`
Memperbarui data pengguna.

- **Request Body (JSON):**
  - `name` (string, opsional)
  - `email` (string, opsional, email, **unique**)
  - `password` (string, opsional, min: 8, **confirmed**): Kirim jika ingin mengubah password.
  - `password_confirmation` (string, opsional): Wajib ada jika `password` diisi.
  - `role` (string, opsional): `admin` atau `guru`.

### DELETE `/api/users/{id}`
Menghapus pengguna.

- **Respons Sukses (204 No Content)**
- **Respons Error (403 Forbidden):** Jika mencoba menghapus akun sendiri.

---

## 4. Manajemen Asesmen (Assessments)
Resource untuk mengelola data asesmen psikologis. Ini adalah endpoint yang kompleks.

### GET `/api/assessments/{id}`
Mengambil detail lengkap satu asesmen.

- **Respons Sukses (200 OK):**
  ```json
  {
      "data": {
          "id": 1,
          "subject": { /* ... objek SubjectResource ... */ },
          "test_date": "2023-10-29",
          "psychologist_name": "Dr. Psikologi",
          "psychological_assessment": {
              "id": 1,
              "cognitive_verbal_score": 110,
              // ... field lainnya
          },
          "talents_mapping": {
              "id": 1,
              "brain_dominance": "Otak Kiri",
              // ... field lainnya
          },
          "scores": [
              {
                  "id": 1,
                  "category": "multiple_intelligence",
                  "scores": {
                      "linguistic": 45,
                      "logical_mathematical": 40,
                      // ...
                  }
              },
              {
                  "id": 2,
                  "category": "personality",
                  "scores": {
                      "extraversion": 80,
                      // ...
                  }
              }
          ],
          "created_at": "2023-10-29 14:00:00",
          "updated_at": "2023-10-29 14:00:00"
      }
  }
  ```

### POST `/api/assessments`
Membuat asesmen baru dengan semua datanya.

- **Request Body (JSON):**
  ```json
  {
      "subject_id": 1,
      "test_date": "2023-10-29",
      "psychologist_name": "Dr. Psikologi",
      "psychological": {
          "cognitive_verbal_score": 110,
          "cognitive_verbal_category": "Tinggi",
          // ... field lainnya
      },
      "talents": {
          "brain_dominance": "Otak Kiri",
          "strengths": "Analitis dan terstruktur.",
          // ... field lainnya
      },
      "scores": {
          "multiple_intelligence": {
              "linguistic": 45,
              "logical_mathematical": 40,
              "musical": 25,
              // ...
          },
          "personality": {
              "extraversion": 80,
              "agreeableness": 75,
              // ...
          },
          "love_language": {
              "words_of_affirmation": 30,
              // ...
          }
      }
  }
  ```

- **Respons Sukses (201 Created):**
  Objek asesmen lengkap yang baru dibuat.

---

*Catatan: Dokumentasi untuk **Reports**, **Invoices**, dan **Services** mengikuti pola yang sama seperti di atas. Jika diperlukan detail lebih lanjut untuk endpoint tersebut, saya bisa menambahkannya.*
