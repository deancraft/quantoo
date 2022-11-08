<?php

namespace Drupal\web_custom;

use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Service description.
 */
class CustomService {

  /**
   * Entity Type Manager service.
   *
   * @var EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * File System
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * @param EntityTypeManagerInterface $entity_type_manager
   *   Entity Type Manager.
   * @param  \Drupal\Core\File\FileSystemInterface  $file_system
   *   File System
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, FileSystemInterface $file_system) {
    $this->entityTypeManager = $entity_type_manager;
    $this->fileSystem = $file_system;
  }

  /**
   * @param  string  $nodeType
   *
   * @return string
   */
  protected function getNodeDetails(string $nodeType): string {
    $values = [
      'type' => $nodeType,
    ];

    $nodes = $this->entityTypeManager
      ->getStorage('node')
      ->loadByProperties($values);

    $nodesDetails = "";
    /** @var Node $node */
    foreach ($nodes as $node) {
      $nodesDetails .= $node->id() . "-" . $node->getTitle() . "; \r\n";
    }

    return $nodesDetails;
  }

  /**
   * @param $nodeType
   *
   * @return bool
   */
  public function saveFileWithNodeDetails($nodeType): bool {

    $nodesDetails = $this->getNodeDetails($nodeType);
    if (empty($nodesDetails)) return FALSE;

    $destination = 'public://file_directory';
    if ($this->fileSystem->prepareDirectory($destination, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS)) {
      /** @var \Drupal\file\FileInterface $file */
      $file = \Drupal::service('file.repository')->writeData(
        $nodesDetails,
        $destination . '/' . $nodeType . '_node_details.txt'
      );

      if ($file instanceof File) {
        return TRUE;
      }
    }

    return FALSE;
  }
}
