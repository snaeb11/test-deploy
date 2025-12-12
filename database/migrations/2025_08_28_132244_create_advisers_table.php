<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('advisers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            $table->timestamps();
        });

        // Insert realistic adviser names
        if (DB::table('advisers')->count() === 0) {
            $programs = DB::table('programs')->get();

            // Sample adviser names for each program
            $adviserNames = [
                'Bachelor of Elementary Education' => ['Dr. Arnulfo S. Masong', 'Froilan G. Legaspino', 'Geramae C. Madera-Mulit', 'Richel P. Albite'],
                'Bachelor of Science in Information Technology Major in Information Security' => ['Mishill D. Cempron', 'Archie A. Cenas', 'Engr. Rey M. De Leon', 'Luchi A. Dela Cruz', 'Dr. Editha L. Hebron', 'Dhally A. Ilisan'],
                'Bachelor of Technical-Vocational Teacher Education' => ['George B. Dela Cruz', 'Ervin Roy V. Matucading', 'Rendel B. Bacan', 'Rizalino O. Dela Torre, Jr.', 'Francisco M. Gubat, Jr.'],
                'Bachelor of Early Childhood Education' => ['Septemberly S. Legaspino', 'Mariel C. Saladaga', 'Shirley Marie V. Llorente', 'Dr. Rosendo R. Meriwan'],
                'Bachelor of Special Needs Education' => ['Dr. Leonila M. Fajardo', 'Dr. Genna J. Carmelo', 'Sarrah Mae V. Priagola', 'Ariane Ray O. Garcia', 'Multibie B. Cantila'],
                'Bachelor of Secondary Education Major in Mathematics' => ['Dr. Benson E. Jomaya', 'Dr. Eleonor T. Guden', 'Dr. Pedro Lopez', 'Dr. Mildin J. Retutas', 'Michael B. Dodongan', 'Lyn G. Enriquez'],
                'Bachelor of Secondary Education Major in English' => ['Dr. Jocelyn A. Matildo', 'Dr. Joan D. Gervacio', 'Dr. Maricel A. Palomata', 'Dr. Donna G. Magallanes', 'Grace O. Elipian', 'Dr. Jose G. Tan, Jr.', 'Hernan G. Pacatang'],
                'Bachelor of Secondary Education Major in Filipino' => ['Mary Jane C. Ningas', 'Dr. Nancy B. Gonzales', 'Dr. Jeanette G. Pedriña', 'John Lerry A. Misa'],
                'Doctor of Education Major in Educational Management' => ['Dr. Virnalisi C. Mindaña', 'Dr. Genna J. Carmelo', 'Dr. Gilbert A. Importante', 'Dr. Jocelyn A. Matildo', 'Dr. Jeanette G. Pedriña'],
                'Master of Education in Educational Management' => ['Dr. Jeanette G. Pedriña', 'Dr. Genna J. Carmelo', 'Dr. Eleonor T. Guden', 'Dr. Gilbert A. Importante', 'Atty. Analyn Q. Villaroman'],
                'Master of Education in Language Teaching Major in English' => ['Dr. Jose G. Tan, Jr.', 'Dr. Joan D. Gervacio', 'Dr. Gilbert A. Importante', 'Dr. Arnulfo S. Masong'],
            ];

            foreach ($programs as $program) {
                if (isset($adviserNames[$program->name])) {
                    foreach ($adviserNames[$program->name] as $adviserName) {
                        DB::table('advisers')->insert([
                            'name' => $adviserName,
                            'program_id' => $program->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advisers');
    }
};
