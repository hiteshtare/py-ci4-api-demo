<?php

namespace App\Controllers;

use App\Models\Task;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Tasks extends ResourceController
{

    use ResponseTrait;

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $model = new Task();
        $data = $model->findAll();
        return $this->respond($data);
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        $model = new Task();
        $data = $model->find($id);
        if (empty($data)) {
            return $this->failNotFound('Item not found');
        } else {
            return $this->respond($data);
        }
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $rules = [
            'name' => 'required',
            'desc' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        } else {
            $data = [
                'name' => $this->request->getVar('name'),
                'desc' => $this->request->getVar('desc')
            ];

            $model = new Task();
            $model->save($data);

            return $this->respondCreated($data);

        }
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        $model = new Task();
        $data = $model->find($id);
        if (empty($data)) {
            return $this->failNotFound('Item not found');
        } else {
            $rules = [
                'name' => 'required',
                'desc' => 'required'
            ];

            if (!$this->validate($rules)) {
                return $this->fail($this->validator->getErrors());
            } else {
                $data = [
                    'name' => $this->request->getVar('name'),
                    'desc' => $this->request->getVar('desc')
                ];

                $model = new Task();
                $model->update($id, $data);

                return $this->respondCreated($data);

            }
        }
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        $model = new Task();
        $data = $model->find($id);
        if (empty($data)) {
            return $this->failNotFound('Item not found');
        } else {
            $model->delete($id);
        }
    }
}
