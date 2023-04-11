<?php

/**
 * Database handling
 */

namespace EasyReader;

use DateTimeImmutable;
use stdClass;
use mysqli;

class Database {

    private mysqli $db;
    private string $sqlDir;

    public function __construct() {
        $this->db = new mysqli(
            EASY_READER_DB_HOST,
            EASY_READER_DB_USER,
            EASY_READER_DB_PASS,
            EASY_READER_DB_NAME
        );
        $this->sqlDir = dirname( __DIR__ ) . '/sql/';
    }

    public function __destruct() {
        // Close the connection
        $this->db->close();
    }

    private function ensureTable( string $tableName, string $patchFile ) {
        $result = $this->db->query( "SHOW TABLES LIKE '$tableName';" );
        if ( $result->num_rows !== 0 ) {
            // Already created
            return;
        }
        $patchContents = file_get_contents( $this->sqlDir . $patchFile );
        $result = $this->db->query( $patchContents );
    }
    public function ensureDatabase() {
        $this->ensureTable( 'users', 'users-table.sql' );
        $this->ensureTable( 'text', 'text-table.sql' );
    }

    public function clearTables() {
        $this->db->query( 'DROP TABLE users' );
        // On the next page view ensureDatabase() will recreate the tables
    }

    public static function setup() {
        // So that the constructor can select the database without errors when
        // it doesn't exist (on docker)
        $mysqli = new mysqli(
            EASY_READER_DB_HOST,
            EASY_READER_DB_USER,
            EASY_READER_DB_PASS
        );
        $mysqli->query(
            "CREATE DATABASE IF NOT EXISTS " . EASY_READER_DB_NAME
        );
        // close the connection
        $mysqli->close();
        $db = new Database;
        $db->ensureDatabase();
    }

    public function accountExists( string $email ): bool {
        return $this->getAccount( $email ) !== null;
    }

    public function getAccount( string $email ): ?stdClass {
        $query = $this->db->prepare(
            'SELECT user_id, user_pass_hash FROM users WHERE user_email = ?'
        );
        $query->bind_param(
            's',
            ...[ $email ]
        );
        $query->execute();
        $result = $query->get_result();
        $rows = $result->fetch_all( MYSQLI_ASSOC );
        if ( count( $rows ) === 0 ) {
            return null;
        }
        return (object)($rows[0]);
    }

    public function createAccount( string $email, string $passHash ): string {
        $query = $this->db->prepare(
            'INSERT INTO users (user_email, user_pass_hash) VALUES (?, ?)'
        );
        $query->bind_param(
            'ss',
            ...[ $email, $passHash ]
        );
        $query->execute();
        return (string)( $this->db->insert_id );
    }

    public function getCurrentUserText( int $userId ): ?string {
        $query = $this->db->prepare(
            'SELECT text_text FROM text WHERE text_user = ?'
        );
        $query->bind_param(
            'd',
            ...[ $userId ]
        );
        $query->execute();
        $result = $query->get_result();
        $rows = $result->fetch_all( MYSQLI_ASSOC );
        if ( count( $rows ) === 0 ) {
            return null;
        }
        return $rows[0]['text_text'];
    }
}