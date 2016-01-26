<?php
namespace App\Models\Interfaces;

interface CrudInterface
{
    public function indexEndpoint(array $options = []);

    public function showEndpoint($id);

    public function createEndpoint(array $data);

    public function updateEndpoint($id, array $data);

    public function replaceEndpoint($id, array $data);

    public function deleteEndpoint($id);
}
