<?php

namespace App\Doctrine;

use App\Entity\TipTicket;
use Symfony\Component\Security\Core\Security;

class TipTicketSetUserListener
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(TipTicket $tipTicket) {
        if ($tipTicket->getUser()) {
            return;
        }

        if ($this->security->getUser()) {
            $tipTicket->setUser($this->security->getUser());
        }
    }
}
