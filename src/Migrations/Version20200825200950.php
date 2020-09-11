<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200825200950 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, title VARCHAR(64) NOT NULL, code VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, movie_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, content VARCHAR(255) NOT NULL, INDEX IDX_5F9E962AA76ED395 (user_id), INDEX IDX_5F9E962A8F93B6FC (movie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movies (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, author_id INT UNSIGNED NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, title VARCHAR(255) NOT NULL, director VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, year DATETIME NOT NULL, INDEX IDX_C61EED3012469DE2 (category_id), INDEX IDX_C61EED30F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movies_tags (movie_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_3CB53BEC8F93B6FC (movie_id), INDEX IDX_3CB53BECBAD26311 (tag_id), PRIMARY KEY(movie_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, code VARCHAR(64) NOT NULL, title VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT UNSIGNED AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX email_idx (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A8F93B6FC FOREIGN KEY (movie_id) REFERENCES movies (id)');
        $this->addSql('ALTER TABLE movies ADD CONSTRAINT FK_C61EED3012469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE movies ADD CONSTRAINT FK_C61EED30F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE movies_tags ADD CONSTRAINT FK_3CB53BEC8F93B6FC FOREIGN KEY (movie_id) REFERENCES movies (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movies_tags ADD CONSTRAINT FK_3CB53BECBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE moviess_tags');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movies DROP FOREIGN KEY FK_C61EED3012469DE2');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A8F93B6FC');
        $this->addSql('ALTER TABLE movies_tags DROP FOREIGN KEY FK_3CB53BEC8F93B6FC');
        $this->addSql('ALTER TABLE movies_tags DROP FOREIGN KEY FK_3CB53BECBAD26311');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AA76ED395');
        $this->addSql('ALTER TABLE movies DROP FOREIGN KEY FK_C61EED30F675F31B');
        $this->addSql('CREATE TABLE moviess_tags (movie_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_391503718F93B6FC (movie_id), INDEX IDX_39150371BAD26311 (tag_id), PRIMARY KEY(movie_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE movies');
        $this->addSql('DROP TABLE movies_tags');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE users');
    }
}
