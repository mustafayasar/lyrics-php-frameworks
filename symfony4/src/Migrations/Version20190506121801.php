<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190506121801 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;


    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE symfony_users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(180) NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E622C377F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

    }

    public function postUp(Schema $schema) : void
    {
        $user = new User();
        $user->setName('Mustafa');
        $user->setUsername('admin');
        $user->setPassword('$2y$13$.3cvRpsJLjMeQ8ECiJcrGuDEoqNYXcfbZuXopBVL6snRTw3VZ0IjK');
        $user->setRoles(['ROLE_ADMIN']);
        $this->connection->getDatabase();

        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($user);

        $em->flush();
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DROP TABLE symfony_users');
    }
}
