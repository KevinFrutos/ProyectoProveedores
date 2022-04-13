<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Proveedor;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class ProveedoresController extends AbstractController
{
    public function index(): Response
    {
        return new Response($this->render('proveedores/index.html.twig'));
    }

    public function create(): Response
    {
        return new Response($this->render('proveedores/create.html.twig'));
    }

    public function store(ManagerRegistry $doctrine, Request $request): RedirectResponse
    {
        if (
            $request->request->get('nombre') !== ""
            && $request->request->get('email')
            && $request->request->get('telefono')
            && $request->request->get('tipo')
            && $request->request->get('activo')
        ) {

            $entityManager = $doctrine->getManager();
            $fecha = new \DateTime('@' . strtotime('now'));

            $proveedor = new Proveedor();
            $proveedor->setNombre($request->request->get('nombre'));
            $proveedor->setEmail($request->request->get('email'));
            $proveedor->setTelefono($request->request->get('telefono'));
            $proveedor->setTipo($request->request->get('tipo'));
            $proveedor->setActivo($request->request->get('activo'));
            $proveedor->setFechaInicial($fecha);
            $proveedor->setFechaUltimaActualizacion($fecha);

            $entityManager->persist($proveedor);
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }
    }

    public function edit(): Response
    {
        return new Response($this->render('proveedores/edit.html.twig'));
    }

    public function update(): RedirectResponse
    {
        return $this->redirectToRoute('index');
    }

    public function destroy(): RedirectResponse
    {
        return $this->redirectToRoute('index');
    }
}
