describe('Menu Tests', () => {
  beforeEach(() => {
    cy.visit('/landing');
  });

  it('should contains title', () => {
    cy.get('.header').contains('Sea of Bottles');
  });

  it('should contains logo', () => {
    cy.get('.header .header__logo').should('exist');
  });

  it('should contains a working create bottle link', () => {
    const link = cy.get('.header .header__menu__item:nth-child(1)');
    link.should('exist');
    link.should('be.visible');
    link.click();
    cy.url().should('include', '/bottle/create');
  });

  it('should contains a working create sailor link', () => {
    const link = cy.get('.header .header__menu__item:nth-child(2)');
    link.should('exist');
    link.should('be.visible');
    link.click();
    cy.url().should('include', '/sailor/create');
  });

  it('should contains a working language selector', () => {
    const selector = cy.get('.header app-translation');
    selector.should('exist');
    selector.should('be.visible');
    cy.get('.jumbotron__baseline').contains('An open project to bring people together');
    selector.get('select').select('fr');
    cy.get('.jumbotron__baseline').contains('Un projet open source pour rapprocher les gens');
  });
});
