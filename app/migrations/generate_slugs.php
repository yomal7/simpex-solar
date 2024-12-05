<?php
require_once '../config/config.php';
require_once '../libraries/Database.php';
require_once '../models/M_Packages.php';

$db = new Database();
$packagesModel = new M_Packages();

// Get all packages without slugs
$db->query('SELECT package_id, title FROM package WHERE slug IS NULL');
$packages = $db->resultSet();

foreach ($packages as $package) {
    $slug = $packagesModel->createSlug($package->title);
    $db->query('UPDATE package SET slug = :slug WHERE package_id = :id');
    $db->bind(':slug', $slug);
    $db->bind(':id', $package->package_id);
    $db->execute();
}

echo "Slug migration completed!\n";