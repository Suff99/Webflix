//We must retain cookies so that between tests, the user remains logged in
//Without the user being logged in, the user cannot make comments and the comments related tests will fail
//without this code, Cypress will delete them!
Cypress.Cookies.defaults({
    preserve: 'webflix_session',
})

// Testing whether a user can successfully use the registration form.
describe('Registration', () => {

    it('successfully loads', () => {
        cy.visit('register.php')
    })

    it('validates date picker functionality', () => {
        cy.get('#dob').click()
        // Test Date
        cy.get('select[class=ui-datepicker-year]').select('1999')
        cy.get('select[class=ui-datepicker-month]').select('10')
        cy.get('a[data-date="11"]').click()
    })

    it('fills out registration details', () => {

        cy.get('#first_name')
            .should('be.visible')
            .type('John')

        cy.get('#last_name')
            .should('be.visible')
            .type('Doe')

        cy.get('#username')
            .should('be.visible')
            .type('JohnDoe99')
        cy.get('#contact')
            .should('be.visible')
            .type('07700900537')

        cy.get('#email')
            .should('be.visible')
            .type('john-doe@example.com')

        cy.get('#password')
            .should('be.visible')
            .type('password_test')

        cy.get('#password_validate')
            .should('be.visible')
            .type('password_test')

        cy.get('button[name="register"]')
            .should('be.visible')
            .click()
    })
})

describe('Log in', () => {
    login()
})


describe('Release Selection', () => {

    it('All Titles - Page Loads', () => {
        cy.visit("titles.php?type=movies")
    })

    it('Release Card - Show Collapsed Content', () => {
        cy.get('img[class="card-img"]').first().scrollIntoView().click({force: true})
    })

    it('Release Card - Show Trailer', () => {
        cy.get('button[data-target="#v_modal"]').first().click({force: true})
        cy.get('div[id="v_modal"]').should('be.visible')
    })

    it('Release Card - Redirect to Release page', () => {
        cy.get('a[name="info"]').first().click({force: true})
        cy.url({decode: true}).should('contain', 'release.php')
    })

})

describe('Review', () => {

    const commentContent = "Wow! This was such a good movie!";

    it('Write Comment', () => {
        cy.get('textarea[id="comment"]').first().scrollIntoView().type(commentContent)
    })

    it('Select Rating (5 Stars)', () => {
        cy.get('input[value="5"]').click({force: true}); //Force click due to mandatory styling.
    })

    it('Submit Review', () => {
        cy.get('button[name="add_comment"]')
            .should('be.visible')
            .click()
    })

    it('Validate Successful Review', () => {
        cy.contains(commentContent)
            .should('exist')
            .click()
    })


    it('Delete Review', () => {
        cy.get('button[name="user_del_comment"]')
            .should('be.visible')
            .click()
    })

    it('Validate Deleted Review', () => {
        cy.contains(commentContent)
            .should('not.exist')
    })

    it('Validate Standard User cannot access administration pages', () => {
        cy.visit('admin.php')
        cy.contains("You do not have permission to access the intended page.")
            .should('exist')
    })

    it('Raise Privileges', () => {
        cy.visit('includes/raise_priv.php?role=admin') // Not really a test
        logout();
    })

})


describe('Guest User Access', () => {

    logout();

    it('Validates guest users cannot access administration pages', () => {
        cy.visit('admin.php')
        cy.contains("You do not have permission to access the intended page.")
            .should('exist')
    })

    it('Validates guest users cannot make comments on releases', () => {
        cy.visit('release.php?id=1')
        cy.get('button[name="add_comment"]')
            .should('not.visible')
    })
})


describe('Administration Tasks', () => {

    loginViaEnv();

    it('Visit Admin page', () => {
        cy.visit('admin.php')
    })

    const categoryName = "Test Category!";

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
});


// Log out when all tests are done. This ensures that the user is logged out
// when a new testing session has begun so that the logging in/registration tests do not fail
describe('Clean up', () => {

    it('Lower Privileges', () => {
        cy.visit('includes/raise_priv.php?role=user') // Not really a test
    })

    logout();
})

function logout() {
    it('successfully logged out', () => {
        cy.visit('https://craig.software/webflix/logout.php')
    })
}

function loginViaEnv(){
    it('Logging in (Admin)', () => {
        const adminEmail = Cypress.env('email')
        const adminPass = Cypress.env('password')

        cy.visit('https://craig.software/webflix/login.php')
        cy.get('input[name=email]')
            .should('be.visible')
            .type(adminEmail)

        cy.get('input[name=password]')
            .should('be.visible')
            .type(adminPass)

        cy.get('button[name=login]')
            .should('be.visible')
            .click()
    })
}

function login() {
    it('Logging in', () => {
        cy.visit('https://craig.software/webflix/login.php')
        cy.get('input[name=email]')
            .should('be.visible')
            .type('john-doe@example.com')

        cy.get('input[name=password]')
            .should('be.visible')
            .type('password_test')

        cy.get('button[name=login]')
            .should('be.visible')
            .click()
    })
}