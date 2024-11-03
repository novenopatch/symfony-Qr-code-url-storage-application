<?php

// src/DataFixtures/QRCodeFixtures.php

namespace App\DataFixtures;

use App\Entity\QRCode;
use App\Entity\Category;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QRCodeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            'Category 1',
            'Category 2',
            'Category 3',
        ];
        $tags = [
            'Tag 1',
            'Tag 2',
            'Tag 3',
            'Tag 4',
            'Tag 5',
        ];

        foreach ($categories as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $category->setSlug($categoryName);
            $manager->persist($category);
        }


        foreach ($tags as $tagName) {
            $tag = new Tag();
            $tag->setName($tagName);
            $manager->persist($tag);
        }

        for ($i = 0; $i < 34; $i++) {
            $qrcode = new QRCode();
            $qrcode->setData('https://chatgpt.com/c/672438f5-73ac-800f-87ba-97f5b5a43d7a' . $i);
            $qrcode->setDescription('Description for QR Code ' . $i);

            $qrcode->setCreatedAt(new \DateTimeImmutable());
            $qrcode->setUpdatedAt(new \DateTimeImmutable());

            $manager->persist($qrcode);
        }

        $manager->flush();
    }


}
