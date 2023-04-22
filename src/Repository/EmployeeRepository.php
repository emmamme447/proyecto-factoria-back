<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Employee>
 *
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function save(Employee $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Employee $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Employee[] Returns an array of Employee filtered by status
//     */
   public function filterEmployeeByStatus($value): array
   {
       return $this->createQueryBuilder('e') // Creamos una query donde 'e' hace referencia a la tabla entidad en este caso employee
       ->innerJoin('e.status', 's') // Utilizamos innerJoin para unir la tabla status con la tabla employee
       ->where('s.title = :title') // Busca por el title de la entidad 'status' por el parametro :title
       ->setParameter('title', $value) // Asigna el value al parametro title
       ->getQuery() // Objeto con la consulta construida
       ->getResult(); // Ejecuta la consulta y obtenemos un conjunto de resultados
   }

   public function filterEmployeeByManager($value): array
   {
       return $this->createQueryBuilder('e') // Creamos una query donde 'e' hace referencia a la tabla entidad en este caso employee
       ->innerJoin('e.manager', 'm') // Utilizamos innerJoin para unir la tabla manager con la tabla employee
       ->where('m.identifying = :identifying') // Busca por el identificador de la entidad 'manager' por el parametro :identifying
       ->setParameter('identifying', $value) // Asigna el value al parametro identifying
       ->getQuery() // Objeto con la consulta construida
       ->getResult(); // Ejecuta la consulta y obtenemos un conjunto de resultados
   }

   public function filterEmployeeByEmail($email): array
   {
       return $this->createQueryBuilder('e') // Creamos una query donde 'e' hace referencia a la tabla entidad en este caso employee
       ->where('e.email = :email') // En la entidad hace una clausula por el campo email
       ->setParameter('email', $email) // Asigna el value al parametro email
       ->getQuery() // Objeto con la consulta construida
       ->getResult(); // Ejecuta la consulta y obtenemos un conjunto de resultados
       ;
   }

   public function formatData($list): array
   {
        $data = [];
        // Del listado suministrado, buscamos los datos en la tabla con los metodos correspondientes
        foreach ($list as $c) {
            $data[] = [
                'id' => $c->getId(),
                'name' => $c->getName(),
                'lastname' => $c->getLastName(),
                'email' => $c->getEmail(),
                'rol' => $c->getRol()->getTitle(),
                'identifying' => $c->getIdentifying(),
                'team' => $c->getTeam()->getTitle(),
                'position' => $c->getPosition()->getTitle(),
                'area' => $c->getArea()->getTitle(),
                'typeOfContract' => $c->getTypeOfContract()->getTitle(),
                'startDate' => $c->getStartDate(),
                'finishDate' => $c->getFinishDate(),
                'manager' => $c->getManager()->getTitle(),
                'period' => $c->getPeriod()->getTitle(),
                'firstPeriod' => $c->getFirstPeriod(),
                'secondPeriod' => $c->getSecondPeriod(),
                'thirdPeriod' => $c->getThirdPeriod(),
                'fourthPeriod' => $c->getFourthPeriod(),
                'fifthPeriod' => $c->getFifthPeriod(),
                'photo' => '/uploads/photo/'.$c->getPhoto(),
                'status' => $c->getStatus()->getTitle(),
            ];
        }
        return $data;
   }
}