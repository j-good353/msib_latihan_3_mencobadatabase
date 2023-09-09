<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        .container {
            max-width: auto;
            margin: 0 auto;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-family: Verdana;
            text-align: center;
        }

        label {
            font-weight: bold;
            font-family: Verdana;
        }

        select,
        input[type='number'] {
            width: 95%;
            padding: 10px;
            margin: 8px 0;
            border: 2px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bfe;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
        }

        #result {
            border: 1px #e77902 solid;
            border-radius: 10px;
            padding: 10px;
        }

        /* css for table */
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px; 
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        .nameclass {
            background-color: rgba(255, 255, 255, 0.6);
        }
    </style>
</head>
<body class="body">
<div class="container">
    <h2>Hitung Nilai Rata-Rata</h2>
    <form id="gradeForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="name">Nama:</label>
        <input class="name" type="text" id="name" name="name" />
        <br />
        <br />

        <label for="course">Mata Kuliah:</label>
        <input type="text" id="course" name="course" />
        <br />
        <br />

        <label for="grade">Grade:</label>
        <select id="grade" name="grade">
            <option value="">Pilih Grade</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
            <option value="E">E</option>
        </select>

        <br />

        <button type="submit">Hitung</button>
    </form>
    <table>
        <tr>
            <th>Nama</th>
            <th>Mata kuliah</th>
            <th>Grade</th>
            <th>Rata-rata</th>
            <th></th>
            <th></th>
        </tr>
        <tbody id="data_tr">
        <?php
        include "koneksi.php";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST["name"];
            $course = $_POST["course"];
            $selectedGrade = $_POST["grade"];
            $gradeValues = ["A" => 4, "B" => 3, "C" => 2, "D" => 1, "E" => 0];

            if (array_key_exists($selectedGrade, $gradeValues)) {
                $gradeValue = $gradeValues[$selectedGrade];
                $totalGradeValues = $gradeValue;
                $average = $totalGradeValues / 1;
                echo "<tr>";
                echo "<td>$name</td>";
                echo "<td>$course</td>";
                echo "<td>$selectedGrade</td>";
                echo "<td>" . number_format($average, 2) . "</td>";
                echo "<td><button onclick=\"editData('$name', '$course', '$selectedGrade', $average)\">Edit</button></td>";
                echo "<td><button onclick=\"deleteData('$name')\">Hapus</button></td>";
                echo "</tr>";
            }

            $sql = "INSERT INTO mahasiswa (nama, mata_kuliah, grade, rata_rata) VALUES ('$name', '$course', '$selectedGrade', $average)";

            if ($koneksi->query($sql) === TRUE) {
                "Data berhasil disimpan.";
            } else {
                "Terjadi kesalahan: " . $koneksi->error;
            }
        }
        ?>
         </tbody>
    </table>
</div>

<script>
    const form = document.getElementById('gradeForm');
    form.addEventListener('submit', calculateAverage);

    const arrayData = []; 

    function calculateAverage(e) {
        e.preventDefault();

        const gradeValues = { A: 4, B: 3, C: 2, D: 1, E: 0 };
        const name = document.getElementById('name').value;
        const course = document.getElementById('course').value;
        const selectedGrade = document.getElementById('grade').value;

        if (selectedGrade in gradeValues) {
            const gradeValue = gradeValues[selectedGrade];

            const totalGradeValues = gradeValue; // For simplicity, you can extend this for multiple subjects
            const average = totalGradeValues / 1; // Assuming 1 subject for now

            addDataToTable(name, course, selectedGrade, average);

            document.getElementById('name').value = '';
            document.getElementById('course').value = '';
            document.getElementById('grade').value = '';
        }
    }

    function addDataToTable(name, course, selectedGrade, average) {
        arrayData.push(`
            <tr>
                <td>${name}</td>
                <td>${course}</td>
                <td>${selectedGrade}</td>
                <td>${average.toFixed(2)}</td>
                <td><button onclick="editData('${name}', '${course}', '${selectedGrade}', ${average})">Edit</button></td>
                <td><button onclick="deleteData('${name}')">Hapus</button></td>
            </tr>
        `);

        document.getElementById('data_tr').innerHTML = arrayData.join('');
    }

    function editData(name, course, selectedGrade, average) {
        document.getElementById('name').value = name;
        document.getElementById('course').value = course;
        document.getElementById('grade').value = selectedGrade;
        
    }

    function deleteData(name) {
        const index = arrayData.findIndex(row => row.includes(name));
        if (index !== -1) {
            arrayData.splice(index, 1);
            document.getElementById('data_tr').innerHTML = arrayData.join('');
        }
    }
</body>
</html>