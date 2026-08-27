<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('catalogo_codigos_postales', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_postal', 5);
            $table->string('estado', 50);
            $table->string('municipio', 100);
            $table->string('zona_cobertura', 150);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['codigo_postal', 'municipio'], 'uk_cp_municipio');
            $table->index(['codigo_postal', 'activo'], 'idx_cp_activo');
        });

        // Insertar catálogo inicial de códigos postales
        $codigosPostales = [
            // San Pedro Cholula
            ['codigo_postal' => '72760', 'estado' => 'Puebla', 'municipio' => 'San Pedro Cholula', 'zona_cobertura' => 'Centro, San Miguel Tianguisnahuac, Santiago Mixquitla, Jesús Tlatempa'],
            ['codigo_postal' => '72763', 'estado' => 'Puebla', 'municipio' => 'San Pedro Cholula', 'zona_cobertura' => 'San Matías Cocoyotla Oriente'],
            ['codigo_postal' => '72764', 'estado' => 'Puebla', 'municipio' => 'San Pedro Cholula', 'zona_cobertura' => 'Santiago Momoxpan, Fracc. La Carcaña Norte'],
            ['codigo_postal' => '72765', 'estado' => 'Puebla', 'municipio' => 'San Pedro Cholula', 'zona_cobertura' => 'La Carcaña, Residencial Cholula'],
            ['codigo_postal' => '72766', 'estado' => 'Puebla', 'municipio' => 'San Pedro Cholula', 'zona_cobertura' => 'Santa María Xixitla, San Juan Calvario, La Magdalena'],
            ['codigo_postal' => '72767', 'estado' => 'Puebla', 'municipio' => 'San Pedro Cholula', 'zona_cobertura' => 'San Cristóbal Tepontla'],
            ['codigo_postal' => '72770', 'estado' => 'Puebla', 'municipio' => 'San Pedro Cholula', 'zona_cobertura' => 'San Matías Cocoyotla'],
            ['codigo_postal' => '72773', 'estado' => 'Puebla', 'municipio' => 'San Pedro Cholula', 'zona_cobertura' => 'San Matías Cocoyotla Norte / Zona Industrial'],
            ['codigo_postal' => '72774', 'estado' => 'Puebla', 'municipio' => 'San Pedro Cholula', 'zona_cobertura' => 'Zona Arqueológica Cholula / Periférico Poniente'],
            ['codigo_postal' => '72775', 'estado' => 'Puebla', 'municipio' => 'San Pedro Cholula', 'zona_cobertura' => 'San Sebastián Tepalcatepec'],
            ['codigo_postal' => '72776', 'estado' => 'Puebla', 'municipio' => 'San Pedro Cholula', 'zona_cobertura' => 'San Diego Cuachayotla, San Cosme Texalo'],
            ['codigo_postal' => '72777', 'estado' => 'Puebla', 'municipio' => 'San Pedro Cholula', 'zona_cobertura' => 'San Francisco Cuapan, Santa Bárbara Almoloya'],
            ['codigo_postal' => '72778', 'estado' => 'Puebla', 'municipio' => 'San Pedro Cholula', 'zona_cobertura' => 'San Gregorio Zacapechpan'],
            ['codigo_postal' => '72779', 'estado' => 'Puebla', 'municipio' => 'San Pedro Cholula', 'zona_cobertura' => 'San Agustín Calvario, San Martín Tlamapa'],

            // San Andrés Cholula
            ['codigo_postal' => '72810', 'estado' => 'Puebla', 'municipio' => 'San Andrés Cholula', 'zona_cobertura' => 'Centro, San Pedro Colomoxco'],
            ['codigo_postal' => '72813', 'estado' => 'Puebla', 'municipio' => 'San Andrés Cholula', 'zona_cobertura' => 'Emiliano Zapata, Gobernadores, Concepción Guadalupe'],
            ['codigo_postal' => '72814', 'estado' => 'Puebla', 'municipio' => 'San Andrés Cholula', 'zona_cobertura' => 'San Bernardino Tlaxcalancingo'],
            ['codigo_postal' => '72815', 'estado' => 'Puebla', 'municipio' => 'San Andrés Cholula', 'zona_cobertura' => 'Morillotla, Radial Zapata, Residencial del Ángel'],
            ['codigo_postal' => '72820', 'estado' => 'Puebla', 'municipio' => 'San Andrés Cholula', 'zona_cobertura' => 'San Luis Tehuiloyocan'],
            ['codigo_postal' => '72824', 'estado' => 'Puebla', 'municipio' => 'San Andrés Cholula', 'zona_cobertura' => 'Santa María Tonantzintla'],
            ['codigo_postal' => '72825', 'estado' => 'Puebla', 'municipio' => 'San Andrés Cholula', 'zona_cobertura' => 'Chipilo de Francisco Javier Mina'],
            ['codigo_postal' => '72828', 'estado' => 'Puebla', 'municipio' => 'San Andrés Cholula', 'zona_cobertura' => 'San Rafael Comac'],
            ['codigo_postal' => '72830', 'estado' => 'Puebla', 'municipio' => 'San Andrés Cholula', 'zona_cobertura' => 'San Francisco Acatepec'],
            ['codigo_postal' => '72834', 'estado' => 'Puebla', 'municipio' => 'San Andrés Cholula', 'zona_cobertura' => 'San Antonio Cacalotepec'],
            ['codigo_postal' => '72835', 'estado' => 'Puebla', 'municipio' => 'San Andrés Cholula', 'zona_cobertura' => 'Reserva Territorial Atlixcáyotl, Ciudad Judicial, La Vista Country Club'],
            ['codigo_postal' => '72836', 'estado' => 'Puebla', 'municipio' => 'San Andrés Cholula', 'zona_cobertura' => 'Lomas de Angelópolis I, II y III'],

            // Cuautlancingo
            ['codigo_postal' => '72700', 'estado' => 'Puebla', 'municipio' => 'Cuautlancingo', 'zona_cobertura' => 'Centro, Barrio del Calvario, Barrio del Alto'],
            ['codigo_postal' => '72703', 'estado' => 'Puebla', 'municipio' => 'Cuautlancingo', 'zona_cobertura' => 'Chautenco, Bosques de Sanctorum'],
            ['codigo_postal' => '72704', 'estado' => 'Puebla', 'municipio' => 'Cuautlancingo', 'zona_cobertura' => 'Los Trojes, San Juan Flor del Bosque'],
            ['codigo_postal' => '72705', 'estado' => 'Puebla', 'municipio' => 'Cuautlancingo', 'zona_cobertura' => 'Galaxias Almecatla, Misiones de San Francisco'],
            ['codigo_postal' => '72710', 'estado' => 'Puebla', 'municipio' => 'Cuautlancingo', 'zona_cobertura' => 'San Lorenzo Almecatla, Parque Industrial FINSA (VW)'],
            ['codigo_postal' => '72720', 'estado' => 'Puebla', 'municipio' => 'Cuautlancingo', 'zona_cobertura' => 'Sanctorum Centro'],
            ['codigo_postal' => '72730', 'estado' => 'Puebla', 'municipio' => 'Cuautlancingo', 'zona_cobertura' => 'Reserva Territorial Quetzalcóatl, Bello Horizonte'],

            // Coronango
            ['codigo_postal' => '72670', 'estado' => 'Puebla', 'municipio' => 'Coronango', 'zona_cobertura' => 'Santa María Coronango Centro'],
            ['codigo_postal' => '72673', 'estado' => 'Puebla', 'municipio' => 'Coronango', 'zona_cobertura' => 'San Martín Zoquiapan'],
            ['codigo_postal' => '72675', 'estado' => 'Puebla', 'municipio' => 'Coronango', 'zona_cobertura' => 'San Francisco Ocotlán, Misiones de San Francisco'],
            ['codigo_postal' => '72677', 'estado' => 'Puebla', 'municipio' => 'Coronango', 'zona_cobertura' => 'San Antonio Mihuacán'],
            ['codigo_postal' => '72678', 'estado' => 'Puebla', 'municipio' => 'Coronango', 'zona_cobertura' => 'Chamizal, San Isidro Coronango'],

            // Juan C. Bonilla
            ['codigo_postal' => '72640', 'estado' => 'Puebla', 'municipio' => 'Juan C. Bonilla', 'zona_cobertura' => 'Cuanalá Centro'],
            ['codigo_postal' => '72643', 'estado' => 'Puebla', 'municipio' => 'Juan C. Bonilla', 'zona_cobertura' => 'San Mateo Cuanalá'],
            ['codigo_postal' => '72645', 'estado' => 'Puebla', 'municipio' => 'Juan C. Bonilla', 'zona_cobertura' => 'Santa María Zacatepec'],
            ['codigo_postal' => '72647', 'estado' => 'Puebla', 'municipio' => 'Juan C. Bonilla', 'zona_cobertura' => 'San Lucas Nextetelco'],
            ['codigo_postal' => '72648', 'estado' => 'Puebla', 'municipio' => 'Juan C. Bonilla', 'zona_cobertura' => 'San Gabriel, Los Ángeles'],

            // San Miguel Xoxtla, Tlaltenango y Domingo Arenas
            ['codigo_postal' => '72620', 'estado' => 'Puebla', 'municipio' => 'San Miguel Xoxtla', 'zona_cobertura' => 'Xoxtla Centro, Parque Industrial Ternium'],
            ['codigo_postal' => '72600', 'estado' => 'Puebla', 'municipio' => 'Tlaltenango', 'zona_cobertura' => 'Tlaltenango Centro'],
            ['codigo_postal' => '72603', 'estado' => 'Puebla', 'municipio' => 'Tlaltenango', 'zona_cobertura' => 'San Pedro San Nicolás'],
            ['codigo_postal' => '72740', 'estado' => 'Puebla', 'municipio' => 'Domingo Arenas', 'zona_cobertura' => 'Domingo Arenas Centro'],

            // Tecuanipan y Santa Isabel Cholula
            ['codigo_postal' => '72850', 'estado' => 'Puebla', 'municipio' => 'San Jerónimo Tecuanipan', 'zona_cobertura' => 'San Jerónimo Tecuanipan Centro'],
            ['codigo_postal' => '72854', 'estado' => 'Puebla', 'municipio' => 'San Jerónimo Tecuanipan', 'zona_cobertura' => 'Los Reyes Tlanechicolpan'],
            ['codigo_postal' => '72855', 'estado' => 'Puebla', 'municipio' => 'San Jerónimo Tecuanipan', 'zona_cobertura' => 'San Antonio Juarez'],
            ['codigo_postal' => '74350', 'estado' => 'Puebla', 'municipio' => 'Santa Isabel Cholula', 'zona_cobertura' => 'Santa Isabel Cholula Centro'],
            ['codigo_postal' => '74353', 'estado' => 'Puebla', 'municipio' => 'Santa Isabel Cholula', 'zona_cobertura' => 'San Martín Tlamapa'],
            ['codigo_postal' => '74355', 'estado' => 'Puebla', 'municipio' => 'Santa Isabel Cholula', 'zona_cobertura' => 'Santa Ana Acozautla'],

            // Puebla Capital
            ['codigo_postal' => '72000', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'Centro Histórico'],
            ['codigo_postal' => '72070', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'San Miguelito, Tamborcito'],
            ['codigo_postal' => '72080', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'Jesús García, Santa María'],
            ['codigo_postal' => '72090', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'Barrio de San Antonio, El Refugio'],
            ['codigo_postal' => '72100', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'La Paz, Amor, Nueva Aurora, Belisario Domínguez'],
            ['codigo_postal' => '72110', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'Las Cuartillas, CAPU, La Loma'],
            ['codigo_postal' => '72120', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'Santa María la Rivera, Cleotilde Torres'],
            ['codigo_postal' => '72130', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'Rancho Colorado, Valle del Rey, San Rafael'],
            ['codigo_postal' => '72140', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'Aquiles Serdán, Villa Posadas, Las Hadas'],
            ['codigo_postal' => '72150', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'Santa Cruz Buenavista, Zavaleta, Las Lajas'],
            ['codigo_postal' => '72160', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'Reforma, Esteban de Antuñano'],
            ['codigo_postal' => '72170', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'La Libertad, Ignacio Romero Vargas'],
            ['codigo_postal' => '72180', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'San Felipe Hueyotlipan, Villa Frontera'],
            ['codigo_postal' => '72190', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'San Jerónimo Caleras, Real del Monte, Barranca Honda'],
            ['codigo_postal' => '72193', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'Guadalupe Caleras'],
            ['codigo_postal' => '72197', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'Covadonga'],
            ['codigo_postal' => '72400', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'Prados Agua Azul, Gabriel Pastor'],
            ['codigo_postal' => '72410', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'El Vergel, Rivera del Tollocan, La Noria'],
            ['codigo_postal' => '72420', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'Benito Juárez, Chulavista, Volcanes'],
            ['codigo_postal' => '72430', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'Mayorazgo, Las Palmas, Agua Santa'],
            ['codigo_postal' => '72440', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'San José Vista Hermosa, Las Ánimas, Angelópolis'],
            ['codigo_postal' => '72450', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'Santa Cruz Los Ángeles, Triángulo Las Ánimas'],
            ['codigo_postal' => '72470', 'estado' => 'Puebla', 'municipio' => 'Puebla', 'zona_cobertura' => 'San José del Puente, Ampliación Reforma'],

            // Estado de Tlaxcala
            ['codigo_postal' => '90740', 'estado' => 'Tlaxcala', 'municipio' => 'Zacatelco', 'zona_cobertura' => 'Zacatelco Centro'],
            ['codigo_postal' => '90743', 'estado' => 'Tlaxcala', 'municipio' => 'Zacatelco', 'zona_cobertura' => 'Guardia'],
            ['codigo_postal' => '90750', 'estado' => 'Tlaxcala', 'municipio' => 'San Lorenzo Axocomanitla', 'zona_cobertura' => 'San Lorenzo Axocomanitla Centro'],
            ['codigo_postal' => '90770', 'estado' => 'Tlaxcala', 'municipio' => 'Santa Catarina Ayometla', 'zona_cobertura' => 'Santa Catarina Ayometla Centro'],
            ['codigo_postal' => '90780', 'estado' => 'Tlaxcala', 'municipio' => 'Papalotla de Xicohténcatl', 'zona_cobertura' => 'Papalotla de Xicohténcatl Centro'],
            ['codigo_postal' => '90784', 'estado' => 'Tlaxcala', 'municipio' => 'Papalotla de Xicohténcatl', 'zona_cobertura' => 'Panzacola Zona Industrial'],
            ['codigo_postal' => '90790', 'estado' => 'Tlaxcala', 'municipio' => 'Tenancingo', 'zona_cobertura' => 'Tenancingo Centro'],
            ['codigo_postal' => '90970', 'estado' => 'Tlaxcala', 'municipio' => 'San Pablo del Monte', 'zona_cobertura' => 'Villa Vicente Guerrero / Centro'],
            ['codigo_postal' => '90974', 'estado' => 'Tlaxcala', 'municipio' => 'San Pablo del Monte', 'zona_cobertura' => 'San Bartolomé, San Nicolás, San Cosme'],
            ['codigo_postal' => '90975', 'estado' => 'Tlaxcala', 'municipio' => 'San Pablo del Monte', 'zona_cobertura' => 'Santiago Michac'],
            ['codigo_postal' => '90976', 'estado' => 'Tlaxcala', 'municipio' => 'San Pablo del Monte', 'zona_cobertura' => 'San Isidro Buensuceso'],
            ['codigo_postal' => '90760', 'estado' => 'Tlaxcala', 'municipio' => 'San Juan Huactzinco', 'zona_cobertura' => 'San Juan Huactzinco Centro'],
        ];

        $now = now();
        foreach ($codigosPostales as &$row) {
            $row['activo'] = true;
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        DB::table('catalogo_codigos_postales')->insert($codigosPostales);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_codigos_postales');
    }
};
