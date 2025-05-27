<?php

namespace App\Tests\Form;

use App\Entity\Book;
use App\Form\BookForm;
use Symfony\Component\Form\Test\TypeTestCase;

class BookFormTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'title' => 'Clean Code',
            'isbn' => '9780132350884',
            'author' => 'Robert C. Martin',
            'image' => 'clean-code.jpg',
        ];

        $model = new Book();

        $form = $this->factory->create(BookForm::class, $model);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals('Clean Code', $model->getTitle());
        $this->assertEquals('9780132350884', $model->getIsbn());
        $this->assertEquals('Robert C. Martin', $model->getAuthor());
        $this->assertEquals('clean-code.jpg', $model->getImage());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $field) {
            $this->assertArrayHasKey($field, $children);
        }
    }
}
