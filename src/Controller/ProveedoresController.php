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
    public function index(Request $request): Response
    {
        $proveedores = $this->getDoctrine()->getRepository(Proveedor::class)->findAll();
        return new Response($this->render('proveedores/index.html.twig', ['proveedores' => $proveedores, 'accion' => $request->get('accion')]));
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
            $proveedor->setActivo($request->request->get('activo') == "true" ? 1 : 0);
            $proveedor->setFechaInicial($fecha);
            $proveedor->setFechaUltimaActualizacion($fecha);

            $entityManager->persist($proveedor);
            $entityManager->flush();

            return $this->redirectToRoute('index', ["accion" => "crear"]);
        }
    }

    public function edit(Request $request): Response
    {
        $id = $request->get('id');
        $proveedor = $this->getDoctrine()->getRepository(Proveedor::class)->findOneBy(['id' => $id]);
        return new Response($this->render('proveedores/edit.html.twig', ['proveedor' => $proveedor]));
    }

    public function update(ManagerRegistry $doctrine, Request $request): RedirectResponse
    {
        if (
            $request->request->get('nombre') !== ""
            && $request->request->get('email') !== ""
            && $request->request->get('telefono') !== ""
            && $request->request->get('tipo') !== ""
            && $request->request->get('activo') !== ""
            && $request->request->get('id') !== ""
        ) {

            $entityManager = $doctrine->getManager();
            $fecha = new \DateTime('@' . strtotime('now'));

            $proveedor = $this->getDoctrine()->getRepository(Proveedor::class)->findOneBy(['id' => $request->request->get('id')]);
            $proveedor->setNombre($request->request->get('nombre'));
            $proveedor->setEmail($request->request->get('email'));
            $proveedor->setTelefono($request->request->get('telefono'));
            $proveedor->setTipo($request->request->get('tipo'));
            $proveedor->setActivo($request->request->get('activo') == "true" ? 1 : 0);
            $proveedor->setFechaUltimaActualizacion($fecha);

            $entityManager->persist($proveedor);
            $entityManager->flush();

            return $this->redirectToRoute('index', ["accion" => "actualizar"]);
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $id = $request->get('id');

        $entityManager = $this->getDoctrine()->getManager();
        $proveedor = $this->getDoctrine()->getRepository(Proveedor::class)->findOneBy(['id' => $id]);
        $entityManager->remove($proveedor);
        $entityManager->flush();

        return $this->redirectToRoute('index', ["accion" => "eliminar"]);
    }
}
