<template>
    <section id="about" class="section bg-light-secondary dark:bg-dark-tertiary">
        <div class="container mx-auto" v-motion :initial="{opacity: 0, y:100}" :visibleOnce="{opacity: 1, y: 0}">
            <div class="flex flex-col md:flex-row gap-12">
                <img class="max-h-96 max-w-xl rounded-lg shadow-xl shadow-light-tertiary dark:shadow-dark-primary" src="img/profile.jpg" alt="Profile" />
                <div class="flex flex-col items-center text-center lg:items-start lg:text-left">
                    <div class="flex-flex-col">
                        <h2 class="text-3xl lg:text-4xl font-medium lg:font-bold mb-3 text-light-quatrenary dark:text-dark-quatrenary">My Journey</h2>
                        <p class="mb-4 text-light-quatrenary dark:text-dark-primary font-bold">Freelance web developer</p>
                        <hr class="mb-8 opacity-10 dark:opacity-5">
                        <p class="mb-8 text-light-quatrenary dark:text-dark-quatrenary">Father of 3 beautiful girls, husband to a wonderful wife, I have grinded in both the construction and technology industries for over 10 years. Coupled with a unique perspective, a thirst for learning all-things webdev, and powered by java (beans), I am thrilled to embrace my next software project.</p>
                        <p class="mb-8 text-light-quatrenary dark:text-dark-quatrenary">My software journey began in the dawn of 2015 when I was inadvertently tasked with improving an Excel spreadsheet and discovered the beauty of what a few lines of code can accomplish. My keen interest spiraled into some intense VBA automation beyond just recording macros...I started to get quite dangerous with it.</p>
                        <p class="mb-8 text-light-quatrenary dark:text-dark-quatrenary">It didn't take long for me to challenge myself even further and dive into the world of web development. Launching my humble journey with humble beginnings in a basic online resume site to full-scale web applications, I am constantly seeking interesting and challenging projects!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
