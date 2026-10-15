<?php

/**
 * Point d'entrée de la cron qui envoie les rappels de vaccin.
 * Coutourne la contrainte d'un hébergement mutualisé.
 *
 */
$_SERVER['argv'] = ['artisan', 'app:vaccine-notification'];

require __DIR__.'/artisan';
