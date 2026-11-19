<?php

return [

    // Limits a donor must meet to be accepted.
    // If a vital is outside these, the donor is deferred.
    'limits' => [
        'min_weight_kg'      => 50,
        'min_hemoglobin'     => 12.5,
        'min_systolic'       => 90,
        'max_systolic'       => 180,
        'min_diastolic'      => 50,
        'max_diastolic'      => 100,
        'min_pulse'          => 50,
        'max_pulse'          => 100,
        'max_temperature_c'  => 37.5,
    ],

    // Values outside these are typing mistakes (like weight 5000),
    // so the form rejects them instead of saving them.
    'input_bounds' => [
        'weight_kg'       => [20, 250],
        'systolic'        => [50, 260],
        'diastolic'       => [30, 160],
        'pulse'           => [30, 220],
        'temperature_c'   => [33, 43],
        'hemoglobin_g_dl' => [4, 25],
    ],

    // How many days to defer a donor, by reason.
    'deferral_days' => [
        'low_hemoglobin' => 90,
        'blood_pressure' => 30,
        'fever'          => 14,
        'low_weight'     => 90,
        'pulse'          => 30,
    ],

];