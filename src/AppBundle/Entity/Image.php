<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
    const PATH_TO_IMAGE_FOLDER = 'public/images/uploaded/';


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=100)
     */
    private $file;

    /**
     * Unmapped property to handle file uploads
     *
     * @var
     * @Assert\Image
     */
    private $uploadedFile;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set file.
     *
     * @param UploadedFile $file
     *
     * @return Image
     */
    public function setUploadedFile(UploadedFile $file = null)
    {
        $this->uploadedFile = $file;

        return $this;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getUploadedFile()
    {
        return $this->uploadedFile ?? ($this->file ? new UploadedFile(self::PATH_TO_IMAGE_FOLDER . $this->file, $this->file) : null);
    }

    /**
     * Set file.
     *
     * @param string $file
     *
     * @return Image
     */
    public function setFile($file = '')
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file.
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Uploads file
     */
    private function upload()
    {
        $uploadedFile = $this->uploadedFile;
        if(null === $uploadedFile) {
            return;
        }

        $originalName = $uploadedFile->getClientOriginalName();

        $uploadedFile->move(
            self::PATH_TO_IMAGE_FOLDER,
            $originalName
        );

        $this->file = $originalName;

        $this->setUploadedFile(null);
    }

    /**
     * Set updated.
     *
     * @param \DateTime|null $updated
     *
     * @return Image
     */
    public function setUpdated($updated = null)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated.
     *
     * @return \DateTime|null
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Lifecycle callback to upload file to the server
     *
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function lifecycleFileLoad()
    {
        $this->upload();
    }

    /**
     * Updates the date of modifying an entity
     *
     * ORM\PreUpdate
     */
    public function refreshUpdated()
    {
        $this->setUpdated(new \DateTime());
    }
}
