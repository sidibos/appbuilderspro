<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProjectController extends Controller
{
    /**
     * @Route("/project-request", name="project_request")
     */
    public function new(Request $request)
    {
        $project = new Project();

        $form = $this->createFormBuilder($project)
            ->add('title', TextType::class)
            ->add('tel', TelType::class)
            ->add('email', EmailType::class)
            ->add('budget', MoneyType::class, ['scale' => 2, 'currency' => 'GBP'])
            ->add('skills_required', TextType::class)
            ->add('description', TextareaType::class, array(
                'attr' => array('class' => 'tinymce', 'rows'=>20, 'cols'=>100),
                )
            )
            ->add('save', SubmitType::class, array('label' => 'Submit Request'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            $project->setStatus('pending');
            $project->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', time())));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('project_success');
        }

        return $this->render('project/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/project-success", name="project_success")
     */
    public function success(Request $request) {
        $message = 'Your project request has been submitted!';
        return $this->render('project/success.html.twig', array(
            'message' => $message,
        ));
    }
}
