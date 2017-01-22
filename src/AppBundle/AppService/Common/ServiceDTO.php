<?php
namespace AppBundle\AppService\Common;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

class ServiceDTO
{
    /**
     * @var bool
     */
    private $isValid;

    /**
     * @var string
     */
    private $message = '';

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var int|string
     */
    private $createdId;

    /**
     * ServiceDTO constructor.
     *
     * @param $isValid
     */
    private function __construct($isValid)
    {
        $this->isValid = $isValid;
    }

    /**
     * @return ServiceDTO
     */
    public static function success()
    {
        $self = new self(true);
        return $self;
    }

    /**
     * @param FormInterface|null $form
     * @return ServiceDTO
     */
    public static function failed($form = null)
    {
        $self = new self(false);
        $self->form = $form;
        return $self;
    }
    
    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @return FormInterface
     */
    public function getForm(): FormInterface
    {
        return $this->form;
    }

    /**
     * @return int|string
     */
    public function getCreatedId()
    {
        return $this->createdId;
    }

    /**
     * @param int|string $createdId
     * @return $this
     */
    public function setCreatedId($createdId)
    {
        $this->createdId = $createdId;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message)
    {
        if ($this->form) {
            $this->form->addError(new FormError($message));
        } else {
            $this->message = $message;
        }
        return $this;
    }
}