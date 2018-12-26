<?php
include_once '../_API/includes.php';

try {
    new Core;
} catch (Exception $e) {
    echo "Failed to create new: {$e->getMessage()}";
}