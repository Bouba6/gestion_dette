<?php

namespace App\DTO;

class ClientDTO
{
    private int $id;
    private ?string $surname;
    private ?string $telephone;
    private ?string $adresse;
    private ?string $search;
    private ?bool $filter;
    // Constructeur
    public function __construct(  ?string $search=null, ?bool $filter=null)
    {
      
        $this->search = $search;
        $this->$filter = $filter;

    }
    public function getFilter(): ?bool
    {
        return $this->filter;
    }

    public function setFilter(?bool $filter): void
    {
        $this->filter = $filter;
    }
    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }

  

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): void
    {
        $this->surname = $surname;
        
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): void
    {
        $this->telephone = $telephone;
        
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): void
    {
        $this->adresse = $adresse;
       
    }
}
