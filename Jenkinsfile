// Определяем, что это Declarative Pipeline
pipeline {
    // Определяем, где будет выполняться конвейер (на любом доступном агенте)
    agent any

    // Настраиваем среду
    environment {
        // Указываем имя образа и тег
        DOCKER_IMAGE = "your-dockerhub-username/your-app-name"
        // Используем встроенную переменную Jenkins для получения короткого хэша Git-коммита
        IMAGE_TAG = "${env.BUILD_NUMBER}-${GIT_COMMIT, length: 8}"
        
        // Переменная для ID учетных данных Docker Hub, сохраненных в Jenkins
        DOCKER_CREDENTIAL_ID = "docker-hub-credentials" 
    }

    // Определяем стадии (Stages) конвейера
    stages {
        
        // 1. Стадия: Получение кода
        stage('Checkout Code') {
            steps {
                // Получает код из репозитория, который запустил конвейер
                checkout scm 
            }
        }

        // 2. Стадия: Сборка образа
        stage('Build Docker Image') {
            steps {
                // Собираем Docker-образ и даем ему тег
                sh "docker build -t ${env.DOCKER_IMAGE}:${env.IMAGE_TAG} ."
            }
        }
        
        // 3. Стадия: Отправка образа в Реестр
        stage('Push Image to Registry') {
            steps {
                // Используем обертку 'withCredentials' для безопасной работы с логином/паролем
                withCredentials([usernamePassword(
                    credentialsId: env.DOCKER_CREDENTIAL_ID,
                    usernameVariable: 'DOCKER_USER',
                    passwordVariable: 'DOCKER_PASS'
                )]) {
                    // 1. Выполняем логин в Docker Hub
                    sh "echo \$DOCKER_PASS | docker login -u \$DOCKER_USER --password-stdin"
                    
                    // 2. Отправляем образ
                    sh "docker push ${env.DOCKER_IMAGE}:${env.IMAGE_TAG}"
                }
                
                // 3. (Опционально) Тегируем и отправляем как "latest"
                sh "docker tag ${env.DOCKER_IMAGE}:${env.IMAGE_TAG} ${env.DOCKER_IMAGE}:latest"
                sh "docker push ${env.DOCKER_IMAGE}:latest"
            }
        }
    }
}
