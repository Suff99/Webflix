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
    } else {
        it('Skipped Administration Tasks due to missing Administration Details', () => {})
    }
})

function loginViaEnv(){
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