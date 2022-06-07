<?php

namespace App\Core\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Core\Entity\FormModel;

class MailerService
{
    /** @var ParameterBagInterface */
    private $params;

    /** @var MailerInterface */
    private $mailer;

    public function __construct(ParameterBagInterface $params , MailerInterface $mailer)
    {
        $this->params = $params;
        $this->mailer = $mailer;
    }

    /**
     * Constuct Mail
     * 
     * @param FormModel $formModel
     * @param array $mailData
     * @return Array $mailData
     */
    public function constructMail(Request $request, FormModel $formModel, $mailData) {
        $mailData['mailTemplate'] = $formModel->getMailTemplate()->getTemplatePath();
        $mailData['sendTo'] = $formModel->getSendTo();
        $mailData['emailFromAddress'] = $this->params->get('default_mail_from_address');
        $mailData['mailFromName'] = $this->params->get('default_mail_from_name');
        foreach ($formModel->getFields() as $field) {
            $fieldType = $field->getType();
            $fieldId = $field->getId();
            $mailData['form_model_field_form_'.$fieldType.'_'.$fieldId] = $request->request->get('form_model_field_form_'.$fieldType.'_'.$fieldId);
        }
        return $mailData;
    }

    /**
     * Constuct Mail
     * 
     * @param FormModel $formModel
     * @param array $mailData
     * @return Array $mailData
     */
    public function sendMail(FormModel $formModel, $mailData) {
        try {
            $email = (new TemplatedEmail())
                ->from(new Address($mailData['emailFromAddress'], $mailData['mailFromName']))
                ->to($mailData['sendTo'])
                ->replyTo('provider@deliwrapp.com')
                ->subject($formModel->getName())
                ->htmlTemplate($mailData['mailTemplate'])
                ->context([
                    'date_creation' => new \DateTime(),
                    'formModel' => $formModel,
                    'mailData' => $mailData,
                ]);
            $this->mailer->send($email);
            return true;
        } catch (\Exception $e) {
            return $e;
        }
    }


}
