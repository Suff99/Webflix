//We must retain cookies so that between tests, the user remains logged in
//Without the user being logged in, the user cannot make comments and the comments related tests will fail
//without this code, Cypress will delete them!
Cypress.Cookies.defaults({
    preserve: 'webflix_session',
})

describe('Administration Tasks', () => {
    if (Cypress.env("email") && Cypress.env("password")) {

        loginViaEnv();

        it('Visit Admin page', () => {
            cy.visit('admin.php')
            cy.contains("Admin Panel").should('exist')
        })

        const categoryName = "Test";

        it('Add Category', () => {
            cy.get('button[name="add_category"]').click()
            cy.get('input[name="category"]').type(categoryName);
            cy.get('textarea[name="description"]').type('This category was added as the result of a test!');
            cy.get('button[name="btn_category"]').click()
        })

        it('Delete Category', () => {
            cy.get('button[name="list_categories"]').click()
            cy.get('a[name="delete_' + categoryName + '"]').first().click()
            cy.contains("Deleted Category").should("exist")
        })

        it ('Movie Form', () => {
            cy.get('a[name="add_movie"]').click()
            cy.url().should('include', 'addtitle.php')
            cy.fixture('star_wars.json').then((movie) => {
                cy.get('input[id="title"]').type(movie.title);
                cy.get('input[id="tagline"]').type(movie.tagline);
                cy.get('textarea[id="description"]').type(movie.description);
                cy.get('input[id="poster_url"]').type(movie.images.poster);
                cy.get('input[id="backdrop_url"]').type(movie.images.backdrop);
                cy.get('select[id="release_type"]').select(movie.release_type);

                cy.get('input[id="tagline"]').type(movie.tagline);
                cy.get('input[id="trailer_id"]').type(movie.trailer);
                cy.get('input[id="watch_link"]').type(movie.trailer);


                cy.get('#release_date').click()
                cy.get('select[class=ui-datepicker-year]').select(movie.release_date.year)
                cy.get('select[class=ui-datepicker-month]').select(movie.release_date.month)
                cy.get('a[data-date="'+movie.release_date.day+'"]').click()
                cy.screenshot()                
            })
        })


            it('successfully logged out', () => {
                cy.visit('https://craig.software/webflix/logout.php')
            })

    } else {
        it('Skipped Administration Tasks due to missing Administration Details', () => {
            cy.writeFile('./cypress.env.json', { email: '', password: '' })
        })
    }
})

function loginViaEnv() {
    it('Logging in (Admin)', () => {

        cy.visit('https://craig.software/webflix/login.php')
        cy.get('input[name=email]')
            .should('be.visible')
            .type(Cypress.env('email'))

        cy.get('input[name=password]')
            .should('be.visible')
            .type(Cypress.env('password'))

        cy.get('button[name=login]')
            .should('be.visible')
            .click()
    })
}