pipeline {
  agent {
    docker {
      image 'composer'
    }

  }
  stages {
    stage('Build') {
      steps {
        sh 'composer install --prefer-source --no-interaction'
      }
    }
    stage('Test') {
      steps {
        sh 'phpunit'
      }
    }
  }
}