<?php

namespace Hookup\Configuration\Remote;

use Symfony\Component\Yaml\Yaml;

/**
 * NASTY!!!!
 */
class GithubConfigurationProvider implements RemoteConfigurationProviderInterface
{
    /**
     * @param string $name
     * @param array $config
     * @return array
     */
    public function get(string $name, array $config): array
    {
        list($user, $repo) = explode('/', $name);

        # TODO ! test all the possible failures here
        try{
            $client = new \Github\Client();
            $client->authenticate($config['github']['token'], null, \Github\Client::AUTH_HTTP_TOKEN);

            $content = $client->api('repo')->contents()->download($user, $repo, '.hookup.yml');

            $config = Yaml::parse($content);

            if (is_array($config)) {
                return $config;
            }
        } catch (\Exception $e) {

        }

        return [];
    }
}
