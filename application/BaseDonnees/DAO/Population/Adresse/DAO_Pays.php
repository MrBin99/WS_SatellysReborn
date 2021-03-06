<?php
    namespace SatellysReborn\BaseDonnees\DAO\Population\Adresse;

    use SatellysReborn\BaseDonnees\DAO\DAO;
    use SatellysReborn\Modeles\Population\Adresse\Pays;

    /**
     * DAO permettant de gérer les pays des adresses en base de données.
     * @package SatellysReborn\BaseDonnees\DAO\Population\Adresse
     */
    class DAO_Pays extends DAO {

        /**
         * Insère l'objet passé en argument dans la base de données s'il
         * n'existe pas.
         * @param Pays $obj l'objet à insérer dans la base de données.
         * @return Pays|bool
         * <ul>
         *     <li>L'objet inséré, si l'insertion a eu lieu.</li>
         *     <li>False sinon.</li>
         * </ul>
         */
        public function insert($obj) {
            // SQL.
            $sql = 'INSERT INTO pays (nom)
                    VALUES (:nom)';

            $res = $this->connexion->insert($sql, array(
                ':nom' => $obj->getNom()
            ));

            // Insertion ok ?
            if ($res) {
                return new Pays($res, $obj->getNom());
            }

            // else
            return false;
        }

        /**
         * Modifie l'objet passé en argument dans la base de données s'il
         * existe.
         * @param Pays $obj l'objet à modifier dans la base de données.
         * @return bool
         * <ul>
         *     <li>True si la modification a eu lieu.</li>
         *     <li>False sinon.</li>
         * </ul>
         */
        public function update($obj) {
            // Pré-condition.
            if (is_null($obj->getId()) || is_null($this->find($obj->getId()))) {
                return false;
            }
            // else

            // SQL.
            $sql = 'UPDATE pays SET
                        nom = :nom
                    WHERE id = :id';

            return $this->connexion->update($sql, array(
                ':nom' => $obj->getNom(),
                ':id' => $obj->getId()
            ));
        }

        /**
         * On ne supprime pas un pays.
         * @param \SatellysReborn\Modeles\Modele $obj non utilisé.
         * @return bool toujours False.
         */
        public function delete($obj) {
            return false;
        }

        /**
         * Sélectionne l'élèment dont la clé primaire est passée en argument
         * s'il existe.
         * @param $cle string la clé primaire de l'objet à sélectionner.
         * @return Pays
         * <ul>
         *     <li>L'objet retounée par la selection.</li>
         *     <li>null si auncun objet n'a été trouvé.</li>
         * </ul>
         */
        public function find($cle) {
            // SQL.
            $sql = 'SELECT nom
                    FROM pays
                    WHERE id = :id';

            $resBD = $this->connexion->select($sql, array(
                ':id' => $cle
            ));

            // Pas de résultats ?
            if (empty($resBD)) {
                return null;
            }

            // else
            return new Pays($cle, $resBD[0]->nom);
        }

        /**
         * Sélectionne le pays dont le nom est passée en argument
         * s'il existe.
         * @param $nom string le nom du pays.
         * @return Pays
         * <ul>
         *     <li>L'objet retounée par la selection.</li>
         *     <li>null si auncun objet n'a été trouvé.</li>
         * </ul>
         */
        public function findNom($nom) {
            // SQL.
            $sql = 'SELECT id, nom
                    FROM pays
                    WHERE enleverAccents(lower(nom)) LIKE 
                          enleverAccents(lower(:nom))';

            $resBD = $this->connexion->select($sql, array(
                ':nom' => '%' . $nom . '%'
            ));

            // Pas de résultats ?
            if (empty($resBD)) {
                return null;
            }

            // else
            return new Pays($resBD[0]->id, $resBD[0]->nom);
        }

        /**
         * Sélectionne tous les éléments de ce type.
         * @return array
         * <ul>
         *     <li>Un tableau d'objets contenant les objets sélectionnés.</li>
         *     <li>null si auncun objet n'a été trouvé.</li>
         * </ul>
         */
        public function findAll() {
            // SQL.
            $sql = 'SELECT id, nom
                    FROM pays';

            $resBD = $this->connexion->select($sql, array());

            // Vide ?
            if (empty($resBD)) {
                return null;
            }
            // else

            // Convertit en objet Pays.
            $res = array();

            // Pour toutes les lignes.
            foreach ($resBD as $obj) {
                array_push($res, new Pays(
                    $obj->id, $obj->nom
                ));
            }

            return $res;
        }
    }