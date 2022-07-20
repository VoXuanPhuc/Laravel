<?php

namespace Encoda\AWSCognito\Services;

class CognitoUserGroupService extends CognitoBaseService
{


    /**
     * @param string $groupName
     * @param string $desc
     * @param int $precedence
     * @param string $roleArn
     * @return \Aws\Result
     */
    public function createGroup( string $groupName, string $desc = '', int $precedence = 0 ) {

        $result = $this->cognitoClient->getClient()->createGroup([
                    'Description' => $groupName,
                    'GroupName' => $this->sanitizeGroupName( $groupName ),
                    'Precedence' => $precedence,
                    'RoleArn' => $this->generateArn( $groupName ),
                    'UserPoolId' => $this->cognitoClient->getUserPoolId(),
                ]);

        return $result->get('Group');
    }

    /**
     * @param string $groupName
     * @return \Aws\Result
     */
    public function find( string $groupName ) {

        $data = [
            'GroupName' => $groupName,
            'UserPoolId' => $this->cognitoClient->getUserPoolId(),
        ];

        $result = $this->cognitoClient->getClient()->getGroup( $data );

        return $result->get('Group');
    }

    /**
     * @param $groupName
     * @param $desc
     * @param null $precedence
     * @return \Aws\Result
     */
    public function updateGroup( $groupName, $desc, $precedence = 0 ) {


        $data = [
            'Description' => $desc,
            'GroupName' => $groupName,
            'Precedence' => $precedence,
            'UserPoolId' => $this->cognitoClient->getUserPoolId(),
        ];

        $result = $this->cognitoClient->getClient()->updateGroup( $data );

        return $result->get('Group');
    }

    /**
     * @param int $limit
     * @return \Aws\Result
     */
    public function listGroups( int $limit = 20 ) {

        $result = $this->cognitoClient->getClient()->listGroups(
            [
                'Limit' => $limit,
                //'NextToken' => '1',
                'UserPoolId' => $this->cognitoClient->getUserPoolId(),
            ]
        );

        return $result->get('Groups');
    }

    /**
     * @param $groupName
     */
    public function deleteGroup( $groupName ) {

        $data = [
            'GroupName' => $groupName,
            'UserPoolId' => $this->cognitoClient->getUserPoolId(),
        ];

        $result = $this->cognitoClient->getClient()->deleteGroup( $data );

        return $result->get('@metadata');
    }


    /**
     * Must follow pattern bellow
     * [\w+=/,.@-]+:[\w+=/,.@-]+:([\w+=/,.@-]*)?:[0-9]+:[\w+=/,.@-]+(:[\w+=/,.@-]+)?(:[\w+=/,.@-]+)?
     * @param $groupName
     * @return string
     */
    protected function generateArn( $groupName ) {


        $arnIdentifiers = [
            'cognito',
            $this->cognitoClient->getUserPoolId(),
            $this->cognitoClient->getClientId(),
            time(),
            'group-' . $this->sanitizeGroupName( $groupName ),

        ];
        return strtolower( 'arn:' . implode( ':', $arnIdentifiers ) );
    }

    /**
     * @param $groupName
     * @return string
     */
    protected function sanitizeGroupName( $groupName ): string
    {
        return preg_replace(  "/[^A-Za-z0-9]/", '-', $groupName );
    }

}
