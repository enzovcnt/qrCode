<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\UX\Dropzone\Form\DropzoneType;

class ExcelUploadForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('excel', DropzoneType::class, [
            'label' => 'Fichier Tableur (XLS, XLSX, CSV, ODS)',
            'mapped' => false,
            'required' => true,
            'constraints' => [
                new File([
                    'mimeTypes' => [
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-excel',
                        'text/csv',
                        'application/vnd.oasis.opendocument.spreadsheet',
                    ],
                    'mimeTypesMessage' => 'Fichier non valide (XLS, XLSX, CSV, ODS uniquement)',
                ]),
            ],
        ]);
    }
}
