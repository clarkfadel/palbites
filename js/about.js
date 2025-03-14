document.addEventListener("DOMContentLoaded", () => {
    const questions = document.querySelectorAll(".toggle");
    const paragraphs = document.querySelectorAll(".about-text");

    paragraphs[0].classList.add("show");

    questions.forEach((question, index) => {
        question.addEventListener("click", () => {
            paragraphs.forEach((p, pIndex) => {
                if (pIndex === index) {
                    p.classList.toggle("show");
                } else {
                    p.classList.remove("show");
                }
            });
        });
    });
});
