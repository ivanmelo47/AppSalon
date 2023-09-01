<?php

function debuguear($variable): string
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html): string
{
    $s = htmlspecialchars($html);
    return $s;
}

function esUltimo(string $actual, string $prximo): bool
{
    if ($actual !== $prximo) {
        return true;
    }
    return false;
}

// Funcion que revisa que el usuario este autenticado
function isAuth(): void
{
    if (!isset($_SESSION['login'])) {
        header('Location: /');
    } elseif ($_SESSION['rol'] === 'admin') {
        header('Location: /admin');
    }
}

function isAdmin(): void
{
    if (isset($_SESSION['rol'])) {
        if ($_SESSION['rol'] !== 'admin') {
            header('Location: /');
        }
    }else{
        header('Location: /');
    }
}