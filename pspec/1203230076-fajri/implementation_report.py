# implementation_report.py
# Modul Laporan & Notifikasi
# Muhammad Fajri Dwi Prasetya Subandi - 1203230076

from flask import Flask, render_template, request, redirect, flash
import mysql.connector
from datetime import datetime

app = Flask(__name__)
app.secret_key = "drainase_pintar_secret"

# -----------------------------
# KONFIGURASI DATABASE
# -----------------------------
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="drainase_pintar"
)
cursor = db.cursor()

# -----------------------------
# MODEL: Laporan dan Notifikasi
# -----------------------------
class Laporan:
    def __init__(self, user_id, deskripsi, lokasi):
        self.user_id = user_id
        self.deskripsi = deskripsi
        self.lokasi = lokasi
        self.waktu = datetime.now()
        self.status = "Menunggu Verifikasi"

    def simpan(self):
        query = """
        INSERT INTO laporan (user_id, deskripsi, lokasi, waktu, status)
        VALUES (%s, %s, %s, %s, %s)
        """
        data = (self.user_id, self.deskripsi, self.lokasi, self.waktu, self.status)
        cursor.execute(query, data)
        db.commit()

class Notifikasi:
    def __init__(self, user_id, pesan):
        self.user_id = user_id
        self.pesan = pesan
        self.waktu = datetime.now()

    def kirim(self):
        query = """
        INSERT INTO notifikasi (user_id, pesan, waktu)
        VALUES (%s, %s, %s)
        """
        data = (self.user_id, self.pesan, self.waktu)
        cursor.execute(query, data)
        db.commit()

# -----------------------------
# ROUTE: Daftar Laporan
# -----------------------------
@app.route('/')
def index():
    cursor.execute("SELECT * FROM laporan ORDER BY waktu DESC")
    laporan = cursor.fetchall()
    return render_template('index.html', laporan=laporan)

# -----------------------------
# ROUTE: Kirim Laporan
# -----------------------------
@app.route('/lapor', methods=['GET', 'POST'])
def lapor():
    if request.method == 'POST':
        user_id = 1  # sementara pakai user_id dummy
        deskripsi = request.form['deskripsi']
        lokasi = request.form['lokasi']

        # Simpan laporan ke database
        laporan = Laporan(user_id, deskripsi, lokasi)
        laporan.simpan()

        # Kirim notifikasi otomatis
        pesan = "Laporan Anda telah diterima dan sedang diverifikasi oleh petugas."
        notif = Notifikasi(user_id, pesan)
        notif.kirim()

        flash("Laporan berhasil dikirim!", "success")
        return redirect('/')
    return render_template('lapor.html')

# -----------------------------
# MAIN
# -----------------------------
if __name__ == '__main__':
    app.run(debug=True)
