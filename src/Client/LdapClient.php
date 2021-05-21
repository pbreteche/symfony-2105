<?php

namespace App\Client;

use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Ldap;

class LdapClient
{
    /**
     * @var string
     */
    private $ldapUri;
    /**
     * @var string
     */
    private $ldapAdminDn;
    /**
     * @var string
     */
    private $ldapAdminPassword;

    /**
     * @var Ldap
     */
    private $connection;

    public function __construct(
        string $ldapUri,
        string $ldapAdminDn,
        string $ldapAdminPassword
    ) {
        $this->ldapUri = $ldapUri;
        $this->ldapAdminDn = $ldapAdminDn;
        $this->ldapAdminPassword = $ldapAdminPassword;
    }

    public function demo()
    {
        $ldap = $this->getConnection();

        $entryManager = $ldap->getEntryManager();

        $entry = new Entry('uid=pbreteche,ou=trainer,dc=example,dc=com', [
            'cn' => 'Pierre Bretéché',
            'mail' => 'pbreteche@example.com',
        ]);

        $entryManager->add($entry);
    }

    public function demo2()
    {
        $ldap = $this->getConnection();

        $query = $ldap->query('dc=example,dc=com', '(&(objectclass=person)(ou=Maintainers))');
        $results = $query->execute();

        foreach ($results as $entry) {
            dump($entry->getAttribute('mail'));
        }
    }

    private function getConnection()
    {
        if (!$this->connection) {
            $this->connection = Ldap::create('ext_ldap', [
                'connection_string' => $this->ldapUri,
            ]);

            $this->connection->bind($this->ldapAdminDn, $this->ldapAdminPassword);
        }

        return $this->connection;
    }
}