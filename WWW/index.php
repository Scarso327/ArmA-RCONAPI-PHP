<?php
include_once '../_API/includes.php';

try {
    new core;
} catch (Exception $e) {
    echo "Failed to create new: {$e->getMessage()}";
}