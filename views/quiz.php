<?php require_once 'layout/header.php'; ?>

<section class="quiz-section">
    <div class="container">
        <div class="quiz-container" data-aos="fade-up">
            <h1>Find Your Perfect Scent</h1>
            <p class="quiz-intro">Answer a few questions to discover your ideal aromatherapy products.</p>
            
            <form id="scentQuiz" action="index.php?page=quiz" method="POST" class="quiz-form">
                <?php if (isset($_GET['error']) && $_GET['error'] === 'missing_answers'): ?>
                    <div class="error-message">Please answer all questions to get your personalized recommendations.</div>
                <?php endif; ?>
                
                <div class="question-container">
                    <div class="question active" data-question="1">
                        <h3>What type of scents do you typically prefer?</h3>
                        <div class="options">
                            <label class="option-card" data-aos="fade-up" data-aos-delay="100">
                                <input type="radio" name="q_preferred_scents" value="floral">
                                <span class="option-content">
                                    <i class="fas fa-flower"></i>
                                    <span>Floral</span>
                                    <small>Rose, Jasmine, Lavender</small>
                                </span>
                            </label>
                            <label class="option-card" data-aos="fade-up" data-aos-delay="200">
                                <input type="radio" name="q_preferred_scents" value="woody">
                                <span class="option-content">
                                    <i class="fas fa-tree"></i>
                                    <span>Woody</span>
                                    <small>Sandalwood, Cedar, Pine</small>
                                </span>
                            </label>
                            <label class="option-card" data-aos="fade-up" data-aos-delay="300">
                                <input type="radio" name="q_preferred_scents" value="citrus">
                                <span class="option-content">
                                    <i class="fas fa-lemon"></i>
                                    <span>Citrus</span>
                                    <small>Orange, Lemon, Bergamot</small>
                                </span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="question" data-question="2">
                        <h3>What's your primary goal with aromatherapy?</h3>
                        <div class="options">
                            <label class="option-card" data-aos="fade-up" data-aos-delay="100">
                                <input type="radio" name="q_mood_goal" value="calming">
                                <span class="option-content">
                                    <i class="fas fa-cloud"></i>
                                    <span>Relaxation</span>
                                    <small>Reduce stress and anxiety</small>
                                </span>
                            </label>
                            <label class="option-card" data-aos="fade-up" data-aos-delay="200">
                                <input type="radio" name="q_mood_goal" value="energizing">
                                <span class="option-content">
                                    <i class="fas fa-bolt"></i>
                                    <span>Energy</span>
                                    <small>Boost mood and vitality</small>
                                </span>
                            </label>
                            <label class="option-card" data-aos="fade-up" data-aos-delay="300">
                                <input type="radio" name="q_mood_goal" value="focusing">
                                <span class="option-content">
                                    <i class="fas fa-brain"></i>
                                    <span>Focus</span>
                                    <small>Improve concentration</small>
                                </span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="question" data-question="3">
                        <h3>When do you typically use aromatherapy products?</h3>
                        <div class="options">
                            <label class="option-card" data-aos="fade-up" data-aos-delay="100">
                                <input type="radio" name="q_daily_routine" value="morning">
                                <span class="option-content">
                                    <i class="fas fa-sun"></i>
                                    <span>Morning</span>
                                    <small>Start the day fresh</small>
                                </span>
                            </label>
                            <label class="option-card" data-aos="fade-up" data-aos-delay="200">
                                <input type="radio" name="q_daily_routine" value="evening">
                                <span class="option-content">
                                    <i class="fas fa-moon"></i>
                                    <span>Evening</span>
                                    <small>Wind down and relax</small>
                                </span>
                            </label>
                            <label class="option-card" data-aos="fade-up" data-aos-delay="300">
                                <input type="radio" name="q_daily_routine" value="throughout">
                                <span class="option-content">
                                    <i class="fas fa-clock"></i>
                                    <span>Throughout the day</span>
                                    <small>Regular aromatherapy</small>
                                </span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="question" data-question="4">
                        <h3>Where do you spend most of your time?</h3>
                        <div class="options">
                            <label class="option-card" data-aos="fade-up" data-aos-delay="100">
                                <input type="radio" name="q_environment" value="indoors">
                                <span class="option-content">
                                    <i class="fas fa-home"></i>
                                    <span>Indoors</span>
                                    <small>Home or office</small>
                                </span>
                            </label>
                            <label class="option-card" data-aos="fade-up" data-aos-delay="200">
                                <input type="radio" name="q_environment" value="outdoors">
                                <span class="option-content">
                                    <i class="fas fa-tree"></i>
                                    <span>Outdoors</span>
                                    <small>Nature and fresh air</small>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="quiz-navigation">
                    <button type="button" class="btn-secondary prev-question" style="display: none;">Previous</button>
                    <button type="button" class="btn-primary next-question">Next</button>
                    <button type="submit" class="btn-primary submit-quiz" style="display: none;">Get My Recommendations</button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('scentQuiz');
    const questions = document.querySelectorAll('.question');
    const nextBtn = document.querySelector('.next-question');
    const prevBtn = document.querySelector('.prev-question');
    const submitBtn = document.querySelector('.submit-quiz');
    let currentQuestion = 0;
    
    // Show first question
    questions[currentQuestion].classList.add('active');
    
    // Handle next button
    nextBtn.addEventListener('click', () => {
        // Validate current question
        const currentInputs = questions[currentQuestion].querySelectorAll('input[type="radio"]');
        let answered = false;
        currentInputs.forEach(input => {
            if (input.checked) answered = true;
        });
        
        if (!answered) {
            alert('Please select an answer before continuing.');
            return;
        }
        
        // Move to next question
        questions[currentQuestion].classList.remove('active');
        currentQuestion++;
        questions[currentQuestion].classList.add('active');
        
        // Update navigation buttons
        prevBtn.style.display = 'block';
        if (currentQuestion === questions.length - 1) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'block';
        }

        // Update progress
        updateProgress();
    });
    
    // Handle previous button
    prevBtn.addEventListener('click', () => {
        questions[currentQuestion].classList.remove('active');
        currentQuestion--;
        questions[currentQuestion].classList.add('active');
        
        // Update navigation buttons
        if (currentQuestion === 0) {
            prevBtn.style.display = 'none';
        }
        nextBtn.style.display = 'block';
        submitBtn.style.display = 'none';

        // Update progress
        updateProgress();
    });

    // Track and display progress
    const progressBar = document.createElement('div');
    progressBar.className = 'quiz-progress';
    form.insertBefore(progressBar, form.firstChild);

    function updateProgress() {
        const progress = ((currentQuestion + 1) / questions.length) * 100;
        progressBar.style.width = `${progress}%`;
        progressBar.setAttribute('aria-valuenow', progress);
    }

    // Initialize progress
    updateProgress();

    // Enhance form submission handling
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Validate all questions are answered
        let allAnswered = true;
        questions.forEach((question, index) => {
            const inputs = question.querySelectorAll('input[type="radio"]');
            let questionAnswered = false;
            inputs.forEach(input => {
                if (input.checked) questionAnswered = true;
            });
            if (!questionAnswered) {
                allAnswered = false;
                question.classList.add('unanswered');
            } else {
                question.classList.remove('unanswered');
            }
        });

        if (!allAnswered) {
            alert('Please answer all questions to get your personalized recommendations.');
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Finding your perfect scent...';
        
        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });
            
            if (!response.ok) throw new Error('Network response was not ok');
            
            const result = await response.text();
            document.querySelector('.quiz-section').innerHTML = result;
            
        } catch (error) {
            console.error('Error:', error);
            alert('There was a problem submitting your quiz. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Get My Recommendations';
        }
    });
});
</script>

<?php require_once 'layout/footer.php'; ?>